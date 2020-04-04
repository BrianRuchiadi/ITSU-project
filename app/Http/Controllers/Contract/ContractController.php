<?php
namespace App\Http\Controllers\Contract;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Models\IrsItemMaster;
use App\Models\CustomerMaster;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\ContractMasterAttachment;
use App\Models\ContractMasterLog;

use App\Models\IrsCity;
use App\Models\IrsState;
use App\Models\IrsCountry;
use App\Models\User;
use App\Models\SystemParamDetail;
use Session;

class ContractController extends Controller
{
    public function showPendingContractList() {
        $contracts = DB::table('contractmaster')
                        ->join('customermaster', 'contractmaster.CNH_CustomerID', '=', 'customermaster.id')
                        ->where('contractmaster.CNH_Status', '=', 'Pending')
                        ->where('contractmaster.deleted_at', '=', null)
                        ->select([
                            'contractmaster.id',
                            'contractmaster.CNH_PostingDate',
                            'contractmaster.CNH_DocNo',
                            'contractmaster.CTOS_verify',
                            'contractmaster.CNH_EmailVerify',
                            'customermaster.Cust_NAME',
                        ])
                        ->paginate(30);

        $user = Auth::user();

        return view('page.contract.pending-contract-list', compact('contracts', 'user'));
    }

    public function getContractDetailByCnhDocNo(Request $request) {
        $sql = "
            SELECT 
                contM.`id`,
                contM.`CNH_DocNo`,
                custM.`Cust_Name`,
                itemM.`IM_Description`,
                contMdtl.`id` AS 'contractmasterdtl_id',
                contMdtl.`CND_Qty`,
                contMdtl.`CND_UnitPrice`,
                contMdtl.`CND_SubTotal`,
                contM.`CNH_NetTotal` AS 'grand_total',
                contM.`CNH_Status`,
                contM.`CNH_InstallAddress1`,
                contM.`CNH_InstallAddress2`,
                contM.`CNH_InstallPostcode`,
                contM.`CNH_InstallCity`,
                contM.`CNH_InstallState`,
                contM.`CNH_InstallCountry`
            FROM
                `contractmaster` contM,
                `customermaster` custM,
                `contractmasterdtl` contMdtl,
                `irs_itemmaster` itemM
            WHERE 1
                AND contM.`CNH_DocNo` = '{$request->contract_no}'
                AND contM.`CNH_Status` = 'Approve'
                AND contM.`do_complete_ind` = 0
                AND custM.`id` = contM.`CNH_CustomerID`
                AND contMdtl.`contractmast_id` = contM.`id`
                AND contMdtl.`CND_ItemID` = itemM.`IM_ID`
        ";

        $contractMaster = DB::select($sql);

        return [
            'data' => $contractMaster
        ];
    }

    public function showSearchResult(Request $request) {
        $contracts = DB::table('customermaster')
                       ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                       ->where('contractmaster.CNH_Status', '=', 'Pending')
                       ->where('contractmaster.deleted_at', '=', null);

        $contracts = (!empty($request->customer)) ? $contracts->where('customermaster.Cust_NAME', 'like', $request->customer . '%') : $contracts; 
        $contracts = (!empty($request->ic_no)) ? $contracts->where('customermaster.Cust_NRIC', 'like', $request->ic_no . '%') : $contracts; 
        $contracts = (!empty($request->tel_no)) ? $contracts->where('customermaster.Cust_Phone1', 'like', $request->tel_no . '%') : $contracts; 
        $contracts = (!empty($request->contract_no)) ? $contracts->where('contractmaster.CNH_DocNo', 'like', $request->contract_no . '%') : $contracts; 

        $contracts = $contracts->select([
                        'contractmaster.id',
                        'contractmaster.CNH_PostingDate',
                        'contractmaster.CNH_DocNo',
                        'contractmaster.CTOS_verify',
                        'contractmaster.CNH_EmailVerify',
                        'customermaster.Cust_NAME',
                    ])->paginate(30);

        $user = Auth::user();

        return view('page.contract.pending-contract-list', compact('contracts', 'user'));
    }
    
    public function showCustomerContractDetail(Request $request, $contractId) {
        $contractDetails = DB::table('customermaster')
                            ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                            ->join('contractmasterdtl', 'contractmaster.id', '=', 'contractmasterdtl.contractmast_id')
                            ->where('contractmaster.id', '=', $contractId)
                            ->select([
                                'contractmaster.id',
                                'contractmaster.CNH_DocNo',
                                'contractmaster.CNH_TotInstPeriod',
                                'contractmaster.CNH_SalesAgent1',
                                'contractmaster.CNH_SalesAgent2',
                                'contractmaster.CTOS_verify',
                                'contractmaster.CNH_NameRef',
                                'contractmaster.CNH_ContactRef',
                                'contractmaster.CNH_EffectiveDay',
                                'contractmaster.CNH_StartDate',
                                'contractmaster.CNH_EndDate',
                                'contractmaster.CNH_ApproveDate',
                                'contractmaster.CNH_CommissionMonth',
                                'contractmaster.CNH_CommissionStartDate',
                                'contractmasterdtl.CND_ItemID',
                                'customermaster.Cust_NAME',
                                'customermaster.Cust_NRIC',
                                'customermaster.Cust_Phone1',
                                'customermaster.Cust_Phone2',
                                'customermaster.Cust_Email',
                                'customermaster.Cust_MainAddress1',
                                'customermaster.Cust_MainAddress2',
                                'customermaster.Cust_MainPostcode',
                                'customermaster.Cust_MainCity',
                                'customermaster.Cust_MainState',
                                'customermaster.Cust_MainCountry',
                            ])->first();

        $contractDetails->start_date = Carbon::today()->toDateString();
        $contractDetails->end_date = Carbon::today()->addMonths($contractDetails->CNH_TotInstPeriod)->toDateString();
                
        $itemMaster = IrsItemMaster::where('IM_ID', $contractDetails->CND_ItemID)
                        ->where('IM_TYPE', '=', '1')
                        ->where('IM_NonSaleItem_YN', '=', 0)
                        ->where('IM_Discontinue_YN', '=', 0)
                        ->where('deleted_at', '=', null)
                        ->first();

        $city = IrsCity::where('CI_ID', $contractDetails->Cust_MainCity)
                  ->where('deleted_at', '=', null)
                  ->first();

        $state = IrsState::where('ST_ID', $contractDetails->Cust_MainState)
                  ->where('deleted_at', '=', null)
                  ->first();

        $country = IrsCountry::where('CO_ID', $contractDetails->Cust_MainCountry)
                  ->where('deleted_at', '=', null)
                  ->first();

        $agent1 = User::where('id', $contractDetails->CNH_SalesAgent1)
                  ->where('branchind', '=', '0')
                  ->where('deleted_at', '=', null)
                  ->first();

        $agent2 = User::where('id', $contractDetails->CNH_SalesAgent2)
                  ->where('branchind', '=', '0')
                  ->where('deleted_at', '=', null)
                  ->first();

        $attachment = ContractMasterAttachment::where('contractmast_id', $contractId)->first();

        $status = 'Pending';
        if ($request->print == 1) {
            return view('page.print.print-contract', compact('contractDetails', 'itemMaster', 'city', 'state', 'country', 'agent1', 'agent2', 'status'));
        } else {
            return view('page.contract.pending-contract-detail', compact('contractDetails', 'itemMaster', 'city', 'state', 'country', 'agent1', 'agent2', 'attachment'));
        }
    }

    public function contractVerifyCTOS(Request $request) {

        ContractMaster::where('id', $request->id)->update(['CTOS_verify' => 1]);
        $contract = ContractMaster::where('id', $request->id)->first();
        Session::flash('showSuccessMessage', "Successfully Verify CTOS ( {$contract->CNH_DocNo} )");
        return ['status' => 'success'];
    }

    public function customerContractDecision(Request $request) {
        
        if ($request->Option == 'Approve') {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|after_or_equal:today',
                'commision_date' => 'required|after_or_equal:start_date',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
            
            ContractMaster::where('id', $request->contract_id)->update([
                'CNH_Status' => 'Approve',
                'CNH_EffectiveDay' => $request->effective_day,
                'CNH_StartDate' => $request->start_date,
                'CNH_EndDate' => $request->end_date,
                'CNH_ApproveDate' => Carbon::now(),
                'CNH_CommissionMonth' => $request->commision_no_of_month,
                'CNH_CommissionStartDate' => $request->commision_date,
                'CNH_InstallAddress1' => $request->cust_mainAddress1,
                'CNH_InstallAddress2' => $request->cust_mainAddress2,
                'CNH_InstallPostcode' => $request->cust_mainPostcode,
                'CNH_InstallCity' => $request->cust_mainCity,
                'CNH_InstallState' => $request->cust_mainState,
                'CNH_InstallCountry' => $request->cust_mainCountry,
                'usr_updated' => Auth::user()->id,
            ]);
        } else if ($request->Option == 'Reject') {
            ContractMaster::where('id', $request->contract_id)->update([
                'CNH_Status' => 'Reject',
                'CNH_RejectDate' => Carbon::now(),
                'CNH_RejectDesc' => $request->reject_reason,
                'usr_updated' => Auth::user()->id,
            ]);
        }

        $contract = ContractMaster::where('id', $request->contract_id)->first();
        $cnsoLogSeqNumber = SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')->select(['param_val'])->first();
        $cnsoLogSeqNumberNew = $cnsoLogSeqNumber->param_val + 1;

        SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')
            ->update(['param_val' => $cnsoLogSeqNumberNew]);
        $contractDtl = ContractMasterDtl::where('contractmast_id', $contract->id)->first();

        ContractMasterLog::create([
            'rcd_grp' => $cnsoLogSeqNumberNew,
            'action' => 'UPD',
            'trx_type' => 'CNSO',
            'subtrx_type' => '',
            'contractmast_id' => $contract->id,
            'branchid' => $contract->branchid,
            'CNH_DocNo' => $contract->CNH_DocNo,
            'CNH_CustomerID' => $contract->CNH_CustomerID,
            'CNH_PostingDate' => $contract->CNH_PostingDate,
            'CNH_DocDate' => $contract->CNH_DocDate,
            'CNH_NameRef' => $contract->CNH_NameRef,
            'CNH_ContactRef' => $contract->CNH_ContactRef,
            'CNH_SalesAgent1' => $contract->CNH_SalesAgent1,
            'CNH_SalesAgent2' => $contract->CNH_SalesAgent2,
            'CNH_TotInstPeriod' => $contract->CNH_TotInstPeriod,
            'CNH_Total' => $contract->CNH_Total,
            'CNH_Tax' => $contract->CNH_Tax,
            'CNH_TaxableAmt' => $contract->CNH_TaxableAmt,
            'CNH_NetTotal' => $contract->CNH_NetTotal,
            'CNH_InstallAddress1' => $contract->CNH_InstallAddress1,
            'CNH_InstallAddress2' => $contract->CNH_InstallAddress2,
            'CNH_InstallAddress3' => $contract->CNH_InstallAddress3,
            'CNH_InstallAddress4' => $contract->CNH_InstallAddress4,
            'CNH_InstallPostcode' => $contract->CNH_InstallPostcode,
            'CNH_InstallCity' => $contract->CNH_InstallCity,
            'CNH_InstallState' => $contract->CNH_InstallState,
            'CNH_InstallCountry' => $contract->CNH_InstallCountry,
            'CNH_TNCInd' => $contract->CNH_TNCInd,
            'CNH_CTOSInd' => $contract->CNH_CTOSInd,
            'CNH_SmsTag' => $contract->CNH_SmsTag,
            'CNH_EmailVerify' => $contract->CNH_EmailVerify,
            'CNH_WarehouseID' => $contract->CNH_WarehouseID,
            'CNH_Status' => $contract->CNH_Status,
            'CTOS_verify' => $contract->CTOS_verify,
            'CTOS_Score' => $contract->CTOS_Score,
            'do_complete_ind' => $contract->do_complete_ind,
            'CNH_EffectiveDay' => $contract->CNH_EffectiveDay,
            'CNH_StartDate' => $contract->CNH_StartDate,
            'CNH_EndDate' => $contract->CNH_EndDate,
            'CNH_ApproveDate' => $contract->CNH_ApproveDate,
            'CNH_RejectDate' => $contract->CNH_RejectDate,
            'CNH_RejectDesc' => $contract->CNH_RejectDesc,
            'CNH_CommissionMonth' => $contract->CNH_CommissionMonth,
            'CNH_CommissionStartDate' => $contract->CNH_CommissionStartDate,
            'contractmastdtl_id' => $contractDtl->id,
            'CND_ItemID' => $contractDtl->CND_ItemID,
            'CND_Description' => $contractDtl->CND_Description,
            'CND_ItemUOMID' => $contractDtl->CND_ItemUOMID,
            'CND_ItemTypeID' => $contractDtl->CND_ItemTypeID,
            'CND_Qty' => $contractDtl->CND_Qty,
            'CND_UnitPrice' => $contractDtl->CND_UnitPrice,
            'CND_SubTotal' => $contractDtl->CND_SubTotal,
            'CND_TaxAmt' => $contractDtl->CND_TaxAmt,
            'CND_TaxableAmt' => $contractDtl->CND_TaxableAmt,
            'CND_Total' => $contractDtl->CND_Total,
            'CND_SerialNo' => $contractDtl->CND_SerialNo,
            'CND_ItemSeq' => $contractDtl->CND_ItemSeq,
            'CND_WarehouseID' => $contractDtl->CND_WarehouseID,
            'CND_BinLocationID' => $contractDtl->CND_BinLocationID,
            'cndeliveryorder_id' => $contractDtl->cndeliveryorder_id,
            'usr_created' => Auth::user()->id
        ]);

        Session::flash('showSuccessMessage', "Successfully {$request->Option} Contract ( {$contract->CNH_DocNo} )");

        return redirect('/contract/pending-contract');
    }
}
