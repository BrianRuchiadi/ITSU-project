<?php
namespace App\Http\Controllers\Contract;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Models\User;
use App\Models\SystemParamDetail;
use App\Models\ContractDeliveryOrder;
use App\Models\ContractDeliveryOrderDtl;
use App\Models\ContractDeliveryOrderLog;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\ContractMasterLog;
use App\Models\CustomerMaster;
use App\Models\IrsCompany;
use App\Models\IrsCity;
use App\Models\IrsState;
use App\Models\IrsCountry;
use Session;

class DeliveryController extends Controller
{

    public function showDeliveryOrder(Request $request) {
        $contractDeliveryOrders = ContractDeliveryOrder::whereNull('deleted_at')->paginate(30);

        return view('page.contract.delivery-order-list', [
            'contract_delivery_orders' => $contractDeliveryOrders
        ]);
    }

    public function showDeliveryOrderDetail(Request $request, $contractDeliveryOrderId) {
        $companyAddress = DB::table('irs_company')
                            ->join('irs_city', 'irs_company.CO_MainCity', '=', 'irs_city.CI_ID')
                            ->join('irs_state', 'irs_company.CO_MainState', '=', 'irs_state.ST_ID')
                            ->join('irs_country', 'irs_company.CO_MainCountry', '=', 'irs_country.CO_ID')
                            ->where('irs_company.CO_Code', '=', env('BRANCH_ID'))
                            ->select([
                                'irs_company.id',
                                'irs_company.CO_Name',
                                'irs_company.CO_MainAddress1',
                                'irs_company.CO_MainAddress2',
                                'irs_company.CO_MainAddress3',
                                'irs_company.CO_MainAddress4',
                                'irs_company.CO_MainPostcode',
                                'irs_company.CO_MainCity',
                                'irs_company.CO_MainState',
                                'irs_company.CO_MainCountry',
                                'irs_company.CO_Phone1',
                                'irs_company.CO_Email',
                                'irs_company.CO_Website',
                                'irs_city.CI_Description',
                                'irs_state.ST_Description',
                                'irs_country.CO_Description'
                            ])->first();

        $deliveryOrder = DB::table('contractdeliveryorder')
                            ->join('contractdeliveryorderdtl', 'contractdeliveryorder.id', '=' , 'contractdeliveryorderdtl.contractdeliveryorder_id')
                            ->join('customermaster', 'contractdeliveryorder.CDOH_CustomerID', '=', 'customermaster.id')
                            ->where('contractdeliveryorder.id', $contractDeliveryOrderId)
                            ->select([
                                'contractdeliveryorder.id',
                                'contractdeliveryorder.CDOH_DocNo',
                                'contractdeliveryorder.CDOH_ContractDocNo',
                                'contractdeliveryorder.CDOH_DocDate',
                                'contractdeliveryorder.CDOH_Address1', 
                                'contractdeliveryorder.CDOH_Address2',
                                'contractdeliveryorder.CDOH_Postcode',
                                'contractdeliveryorder.CDOH_City',
                                'contractdeliveryorder.CDOH_State',
                                'contractdeliveryorder.CDOH_Country',
                                'contractdeliveryorder.CDOH_InstallAddress1',
                                'contractdeliveryorder.CDOH_InstallAddress2',
                                'contractdeliveryorder.CDOH_InstallPostcode',
                                'contractdeliveryorder.CDOH_InstallCity',
                                'contractdeliveryorder.CDOH_InstallState',
                                'contractdeliveryorder.CDOH_InstallCountry',
                                'contractdeliveryorderdtl.CDOD_Description',
                                'contractdeliveryorderdtl.CDOD_Qty',
                                'contractdeliveryorderdtl.CDOD_SerialNo',
                                'customermaster.Cust_NAME'
                            ])->first();

        $deliveryOrder->City_Description = IrsCity::where('CI_ID', $deliveryOrder->CDOH_City)->pluck('CI_Description')->first();
        $deliveryOrder->State_Description = IrsState::where('ST_ID', $deliveryOrder->CDOH_State)->pluck('ST_Description')->first();
        $deliveryOrder->Country_Description = IrsCountry::where('CO_ID', $deliveryOrder->CDOH_Country)->pluck('CO_Description')->first();

        $deliveryOrder->InstallCity_Description = IrsCity::where('CI_ID', $deliveryOrder->CDOH_InstallCity)->pluck('CI_Description')->first();
        $deliveryOrder->InstallState_Description = IrsState::where('ST_ID', $deliveryOrder->CDOH_InstallState)->pluck('ST_Description')->first();
        $deliveryOrder->InstallCountry_Description = IrsCountry::where('CO_ID', $deliveryOrder->CDOH_InstallCountry)->pluck('CO_Description')->first();

        $printDate = Carbon::now()->toDateString();
        return view('page.contract.delivery-order-detail', compact('companyAddress', 'deliveryOrder', 'printDate'));

    }

    public function showCreateDeliveryOrder(Request $request) {
        return view('page.contract.delivery-order-create');
    }

    public function createDeliveryOrder(Request $request) {
        // validation
        $validator = Validator::make($request->all(), [
            'contract_no' => 'required|string|exists:contractmaster,CNH_DocNo',
            'serial_no' => 'required|string|min:1',

            'delivery_date' => 'required|date',
            'delivery_address_1' => 'required|string|min:5',
            'delivery_address_2' => 'nullable|string|min:5',
            'delivery_postcode' => 'required|string|min:4|max:10',
            'delivery_country' => 'required|exists:irs_country,CO_ID',
            'delivery_state' => 'required|exists:irs_state,ST_ID',
            'delivery_city' => 'required|exists:irs_city,CI_ID',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $params = $request->all();
        DB::beginTransaction();
        
        try {
            // essential tables
            $contractMaster = ContractMaster::where('CNH_DocNo', $request->contract_no)->first();
            $contractMasterDetail = ContractMasterDtl::where('contractmast_id', $contractMaster->id)->first();
            $customerMaster = CustomerMaster::where('id', $contractMaster->CNH_CustomerID)->first();
            // get CDOH_DocNo value
            $cndoDocSeq = SystemParamDetail::where('sysparam_cd', 'CNDODOCSEQ')->select(['param_val'])->first();
            $cndoDocSeqNew = +$cndoDocSeq->param_val + 1;

            SystemParamDetail::where('sysparam_cd', 'CNDODOCSEQ')->update(['param_val' => $cndoDocSeqNew]);
            $cndoDocSeqNew = str_pad((string)$cndoDocSeqNew, 8, "0", STR_PAD_LEFT);
            
            $cndoDocPrefix = SystemParamDetail::where('sysparam_cd', 'CNDODOCPREFIX')->select(['param_val'])->first();
            $cndoDocPrefix = $cndoDocPrefix->param_val;
           
            $cdohDocNo = "{$cndoDocPrefix}-{$cndoDocSeqNew}";
            
            $contractDeliveryOrder = ContractDeliveryOrder::create([
                'branchid' => $contractMaster->branchid,
                'CDOH_DocNo' => $cdohDocNo,
                'CDOH_CustomerID' => $contractMaster->CNH_CustomerID,
                'contractmast_id' => $contractMaster->id,
                'CDOH_ContractDocNo' => $contractMaster->CNH_DocNo,
                'CDOH_Note' => $contractMaster->CNH_Note,
                'CDOH_PostingDate' => Carbon::now(),
                'CDOH_DocDate' => $request->delivery_date,
                'CDOH_Address1' => $customerMaster->Cust_MainAddress1,
                'CDOH_Address2' => $customerMaster->Cust_MainAddress2,
                'CDOH_Address3' => $customerMaster->Cust_MainAddress3,
                'CDOH_Address4' => $customerMaster->Cust_MainAddress4,
                'CDOH_Postcode' => $customerMaster->Cust_MainPostcode,
                'CDOH_City' => $customerMaster->Cust_MainCity,
                'CDOH_State' => $customerMaster->Cust_MainState,
                'CDOH_Country' => $customerMaster->Cust_MainCountry,
                'CDOH_InstallAddress1' => $request->delivery_address_1,
                'CDOH_InstallAddress2' => $request->delivery_address_2,
                'CDOH_InstallAddress3' => null,
                'CDOH_InstallAddress4' => null,
                'CDOH_InstallPostcode' => $request->delivery_postcode,
                'CDOH_InstallCity' => $request->delivery_city,
                'CDOH_InstallState' => $request->delivery_state,
                'CDOH_InstallCountry' => $request->delivery_country,
                'CDOH_WarehouseID' => $contractMaster->CNH_WarehouseID,
                'CDOH_Total' => $contractMaster->CNH_Total,
                'CDOH_TaxAmt' => $contractMaster->CNH_Tax,
                'CDOH_TaxableAmt' => $contractMaster->CNH_TaxableAmt,
                'CDOH_NetTotal' => $contractMaster->CNH_NetTotal,
                'CDOH_SalesAgent1' => $contractMaster->CNH_SalesAgent1,
                'CDOH_SalesAgent2' => $contractMaster->CNH_SalesAgent2,
                'usr_created' => Auth::user()->id
            ]);

            $contractDeliveryOrderDtl = ContractDeliveryOrderDtl::create([
                'contractdeliveryorder_id' => $contractDeliveryOrder->id,
                'CDOD_ItemID' => $contractMasterDetail->CND_ItemID,
                'CDOD_Description' => $contractMasterDetail->CND_Description,
                'CDOD_ItemUOMID' => $contractMasterDetail->CND_ItemUOMID,
                'CDOD_ItemTypeID' => $contractMasterDetail->CND_ItemTypeID,
                'CDOD_WarehouseID' => $contractMasterDetail->CND_WarehouseID,
                'CDOD_BinLocationID' => $contractMasterDetail->CND_BinLocationID,
                'CDOD_Qty' => $contractMasterDetail->CND_Qty,
                'CDOD_UnitPrice' => $contractMasterDetail->CND_UnitPrice,
                'CDOD_SubTotal' => $contractMasterDetail->CND_SubTotal,
                'CDOD_TaxAmt' => $contractMasterDetail->CND_TaxAmt,
                'CDOD_TaxableAmt' => $contractMasterDetail->CND_TaxableAmt,
                'CDOD_Total' => $contractMasterDetail->CND_Total,
                'CDOD_SerialNo' => $params['serial_no'],
                'CDOD_Item_Seq' => 1,
                'cn_Item_Seq' => $contractMasterDetail->CND_ItemSeq,
                'usr_created' => Auth::user()->id
            ]);

            ContractMasterDtl::where('contractmast_id', $contractMaster->id)
                ->where('CND_ItemID', $contractDeliveryOrderDtl->CDOD_ItemID)
                ->where('CND_ItemSeq', $contractDeliveryOrderDtl->cn_Item_Seq)
                ->update([
                    'CND_SerialNo' => $params['serial_no'],
                    'cndeliveryorder_id' => $contractDeliveryOrder->id,
                    'usr_updated' => Auth::user()->id
                ]);

            $openDeliveryOrders = ContractMasterDtl::where('contractmast_id', $contractMaster->id)
                    ->whereNull('cndeliveryorder_id')
                    ->orWhere('cndeliveryorder_id', '>', 0)
                    ->get();

            if ($openDeliveryOrders->count() > 0) {
                // 4.3.6 update contractmaster
                ContractMaster::where('id', $contractMaster->id)
                    ->whereNull('deleted_at')
                    ->update([
                        'do_complete_ind' => 1,
                        'usr_updated' => Auth::user()->id,
                        'CNH_InstallAddress1' => $request->delivery_address_1,
                        'CNH_InstallAddress2' => $request->delivery_address_2,
                        'CNH_InstallPostcode' => $request->delivery_postcode,
                        'CNH_InstallCity' => $request->delivery_city,
                        'CNH_InstallState' => $request->delivery_state,
                        'CNH_InstallCountry' => $request->delivery_country,
                    ]);

                $contractMaster = ContractMaster::where('id', $contractMaster->id)->first();
                                // store into contractmasterlog
                $cnsoLogSeq = SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')->select(['param_val'])->first();
                $cnsoLogSeqNew = $cnsoLogSeq->param_val + 1;

                SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')
                    ->update(['param_val' => $cnsoLogSeqNew]);

                $contractMasterLog = ContractMasterLog::create([
                    'rcd_grp' => $cnsoLogSeqNew,
                    'action' => 'UPD',
                    'trx_type' => 'CNSO',
                    'subtrx_type' => '',
                    'contractmast_id' => $contractMaster->id,
                    'branchid' => $contractMaster->branchid,
                    'CNH_DocNo' => $contractMaster->CNH_DocNo,
                    'CNH_CustomerID' => $contractMaster->CNH_CustomerID,
                    'CNH_Note' => $contractMaster->CNH_Note,
                    'CNH_PostingDate' => $contractMaster->CNH_PostingDate,
                    'CNH_DocDate' => $contractMaster->CNH_DocDate,
                    'CNH_NameRef' => $contractMaster->CNH_NameRef,
                    'CNH_ContactRef' => $contractMaster->CNH_ContactRef,
                    'CNH_SalesAgent1' => $contractMaster->CNH_SalesAgent1,
                    'CNH_SalesAgent2' => $contractMaster->CNH_SalesAgent2,
                    'CNH_TotInstPeriod' => $contractMaster->CNH_TotInstPeriod,
                    'CNH_Total' => $contractMaster->CNH_Total,
                    'CNH_Tax' => $contractMaster->CNH_Tax,
                    'CNH_TaxableAmt' => $contractMaster->CNH_TaxableAmt,
                    'CNH_NetTotal' => $contractMaster->CNH_NetTotal,
                    'CNH_Address1' => $contractMaster->CNH_Address1,
                    'CNH_Address2' => $contractMaster->CNH_Address2,
                    'CNH_Address3' => $contractMaster->CNH_Address3,
                    'CNH_Address4' => $contractMaster->CNH_Address4,
                    'CNH_Postcode' => $contractMaster->CNH_Postcode,
                    'CNH_City' => $contractMaster->CNH_City,
                    'CNH_State' => $contractMaster->CNH_State,
                    'CNH_Country' => $contractMaster->CNH_Country,
                    'CNH_InstallAddress1' => $contractMaster->CNH_InstallAddress1,
                    'CNH_InstallAddress2' => $contractMaster->CNH_InstallAddress2,
                    'CNH_InstallAddress3' => $contractMaster->CNH_InstallAddress3,
                    'CNH_InstallAddress4' => $contractMaster->CNH_InstallAddress4,
                    'CNH_InstallPostcode' => $contractMaster->CNH_InstallPostcode,
                    'CNH_InstallCity' => $contractMaster->CNH_InstallCity,
                    'CNH_InstallState' => $contractMaster->CNH_InstallState,
                    'CNH_InstallCountry' => $contractMaster->CNH_InstallCountry,
                    'CNH_TNCInd' => $contractMaster->CNH_TNCInd,
                    'CNH_CTOSInd' => $contractMaster->CNH_CTOSInd,
                    'CNH_SmsTag' => $contractMaster->CNH_SmsTag,
                    'CNH_EmailVerify' => $contractMaster->CNH_EmailVerify,
                    'CNH_WarehouseID' => $contractMaster->CNH_WarehouseID,
                    'CNH_Status' => $contractMaster->CNH_Status,
                    'CTOS_verify' => $contractMaster->CTOS_verify,
                    'CTOS_Score' => $contractMaster->CTOS_Score,
                    'do_complete_ind' => $contractMaster->do_complete_ind,
                    'CNH_EffectiveDay' => $contractMaster->CNH_EffectiveDay,
                    'CNH_StartDate' => $contractMaster->CNH_StartDate,
                    'CNH_EndDate' => $contractMaster->CNH_EndDate,
                    'CNH_ApproveDate' => $contractMaster->CNH_ApproveDate,
                    'CNH_RejectDate' => $contractMaster->CNH_RejectDate,
                    'CNH_RejectDesc' => $contractMaster->CNH_RejectDesc,
                    'CNH_CommissionMonth' => $contractMaster->CNH_CommissionMonth,
                    'contractmastdtl_id' => $contractMasterDetail->id,
                    'CND_ItemID' => $contractMasterDetail->CND_ItemID,
                    'CND_Description' => $contractMasterDetail->CND_Description,
                    'CND_ItemUOMID' => $contractMasterDetail->CND_ItemUOMID,
                    'CND_ItemTypeID' => $contractMasterDetail->CND_ItemTypeID,
                    'CND_Qty' => $contractMasterDetail->CND_Qty,
                    'CND_UnitPrice' => $contractMasterDetail->CND_UnitPrice,
                    'CND_SubTotal' => $contractMasterDetail->CND_SubTotal,
                    'CND_TaxAmt' => $contractMasterDetail->CND_TaxAmt,
                    'CND_TaxableAmt' => $contractMasterDetail->CND_TaxableAmt,
                    'CND_Total' => $contractMasterDetail->CND_Total,
                    'CND_SerialNo' => $params['serial_no'],
                    'CND_ItemSeq' => $contractMasterDetail->CND_ItemSeq,
                    'CND_WarehouseID' => $contractMasterDetail->CND_WarehouseID,
                    'CND_BinLocationID' => $contractMasterDetail->CND_BinLocationID,
                    'cndeliveryorder_id' => $contractDeliveryOrder->id,
                    'usr_created' => Auth::user()->id
                ]);
            }

            $contractDeliveryOrderLog = ContractDeliveryOrderLog::create([
                'action' => 'ADD',
                'trx_type' => 'DO',
                'subtrx_type' => '',
                'branchid' => $contractDeliveryOrder->branchid,
                'CDOH_DocNo' => $contractDeliveryOrder->CDOH_DocNo,
                'CDOH_CustomerID' => $contractDeliveryOrder->CDOH_CustomerID,
                'contractmast_id' => $contractDeliveryOrder->contractmast_id,
                'CDOH_ContractDocNo' => $contractDeliveryOrder->CDOH_ContractDocNo,
                'CDOH_Note' => $contractDeliveryOrder->CDOH_Note,
                'CDOH_PostingDate' => $contractDeliveryOrder->CDOH_PostingDate,
                'CDOH_DocDate' => $contractDeliveryOrder->CDOH_DocDate,
                'CDOH_Address1' => $contractDeliveryOrder->CDOH_Address1,
                'CDOH_Address2' => $contractDeliveryOrder->CDOH_Address2,
                'CDOH_Address3' => $contractDeliveryOrder->CDOH_Address3,
                'CDOH_Address4' => $contractDeliveryOrder->CDOH_Address4,
                'CDOH_Postcode' => $contractDeliveryOrder->CDOH_Postcode,
                'CDOH_City' => $contractDeliveryOrder->CDOH_City,
                'CDOH_State' => $contractDeliveryOrder->CDOH_State,
                'CDOH_Country' => $contractDeliveryOrder->CDOH_Country,
                'CDOH_InstallAddress1' => $contractDeliveryOrder->CDOH_InstallAddress1,
                'CDOH_InstallAddress2' => $contractDeliveryOrder->CDOH_InstallAddress2,
                'CDOH_InstallAddress3' => $contractDeliveryOrder->CDOH_InstallAddress3,
                'CDOH_InstallAddress4' => $contractDeliveryOrder->CDOH_InstallAddress4,
                'CDOH_InstallPostcode' => $contractDeliveryOrder->CDOH_InstallPostcode,
                'CDOH_InstallCity' => $contractDeliveryOrder->CDOH_InstallCity,
                'CDOH_InstallState' => $contractDeliveryOrder->CDOH_InstallState,
                'CDOH_InstallCountry' => $contractDeliveryOrder->CDOH_InstallCountry,
                'CDOH_WarehouseID' => $contractDeliveryOrder->CDOH_WarehouseID,
                'CDOH_Total' => $contractDeliveryOrder->CDOH_Total,
                'CDOH_TaxAmt' => $contractDeliveryOrder->CDOH_TaxAmt,
                'CDOH_TaxableAmt' => $contractDeliveryOrder->CDOH_TaxableAmt,
                'CDOH_NetTotal' => $contractDeliveryOrder->CDOH_NetTotal,
                'CDOH_SalesAgent1' => $contractDeliveryOrder->CDOH_SalesAgent1,
                'CDOH_SalesAgent2' => $contractDeliveryOrder->CDOH_SalesAgent2,
                'contractdeliveryorderdtl_id' => $contractDeliveryOrderDtl->id,
                'CDOD_ItemID' => $contractDeliveryOrderDtl->CDOD_ItemID,
                'CDOD_Description' => $contractDeliveryOrderDtl->CDOD_Description,
                'CDOD_ItemUOMID' => $contractDeliveryOrderDtl->CDOD_ItemUOMID,
                'CDOD_ItemTypeID' => $contractDeliveryOrderDtl->CDOD_ItemTypeID,
                'CDOD_WarehouseID' => $contractDeliveryOrderDtl->CDOD_WarehouseID,
                'CDOD_BinLocationID' => $contractDeliveryOrderDtl->CDOD_BinLocationID,
                'CDOD_Qty' => $contractDeliveryOrderDtl->CDOD_Qty,
                'CDOD_UnitPrice' => $contractDeliveryOrderDtl->CDOD_UnitPrice,
                'CDOD_SubTotal' => $contractDeliveryOrderDtl->CDOD_SubTotal,
                'CDOD_TaxAmt' => $contractDeliveryOrderDtl->CDOD_TaxAmt,
                'CDOD_TaxableAmt' => $contractDeliveryOrderDtl->CDOD_TaxableAmt,
                'CDOD_Total' => $contractDeliveryOrderDtl->CDOD_Total,
                'CDOD_SerialNo' => $contractDeliveryOrderDtl->CDOD_SerialNo,
                'CDOD_Item_Seq' => $contractDeliveryOrderDtl->CDOD_Item_Seq,
                'cn_Item_Seq' => $contractDeliveryOrderDtl->cn_Item_Seq,
                'usr_created' => Auth::user()->id
            ]);
            
            DB::commit();

            $client = new Client(['http_errors' => false]); 
            $response = $client->post(config('app.pos_web_link') . "api/deliveryorder/{$contractDeliveryOrder->id}", [
                'form_params' => [
                    'secret' => config('app.pos_app_key')
                ]
            ]);

            $statusCode = $response->getStatusCode();

            switch ($statusCode) {
                case 200: 
                    ContractDeliveryOrder::where('id', $contractDeliveryOrder->id)->update([
                        'pos_api_ind' => 1,
                        'usr_updated' => Auth::user()->id
                    ]);
                    break;
                default:
                    ContractDeliveryOrder::where('id', $contractDeliveryOrder->id)->update([
                        'pos_api_ind' => 0,
                        'usr_updated' => Auth::user()->id
                    ]);
                    break;
            }

            Session::flash('showSuccessMessage', "Successfully Create Delivery Order For {$params['contract_no']} . POS API Status : {$statusCode}");
            return redirect('/contract/delivery-order');
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function resubmitDeliveryOrder(Request $request, ContractDeliveryOrder $contractDeliveryOrder) {
        $client = new Client(['http_errors' => false]); 
        $response = $client->post(config('app.pos_web_link') . "api/deliveryorder/{$contractDeliveryOrder->id}", [
            'form_params' => [
                'secret' => config('app.pos_app_key')
            ]
        ]);

        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 200: 
                $contractDeliveryOrder->update([
                    'pos_api_ind' => 1,
                    'usr_updated' => Auth::user()->id
                ]);

                Session::flash('showSuccessMessage', 'Successfully update POS API');
                return [
                    'status' => 'success',
                    'data' => $contractDeliveryOrder,
                    'successMessage' => 'Successfully updated POS API'
                ];
                break;

            case 400 :
            case 401 :
            default:
                $contractDeliveryOrder->update([
                    'pos_api_ind' => 0,
                    'usr_updated' => Auth::user()->id
                ]);

                $errorMessage = ($statusCode == 400) ? 'invalid parameter' : 'invalid secret';
                return [
                    'status' => 'failed',
                    'data' => $contractDeliveryOrder,
                    'errorMessage' => $errorMessage
                ];
                break;
        }

        
    }
}
