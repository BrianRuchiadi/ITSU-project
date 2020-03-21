<?php
namespace App\Http\Controllers\Customer;

use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Hashids\Hashids;
use Carbon\Carbon;
use Session;
use DB;

use App\Models\IrsSalesBranch;
use App\Models\IrsItemMaster;
use App\Models\IrsItemUom;
use App\Models\CustomerMaster;
use App\Models\CustomerUserMap;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\SystemParamDetail;
use App\Models\ContractMasterAttachment;
use App\Models\ContractMasterLog;


class CustomerController extends Controller
{
    public function getReferralLink(Request $request) {
        $hashids = new Hashids(config('app.salt'), 5);
        $hashedId = $hashids->encode(Auth::user()->id);
        
        return [
            'url' => config('app.url') . '/register?ref=' . $hashedId
        ];
    }

    public function saveDataInSession($request) {
            // START : throw back all the already validated request, so that it will be included in next request
            Session::flash('product', $request->product);
            Session::flash('no_of_installment_month', $request->no_of_installment_month);
            Session::flash('name_of_applicant', $request->name_of_applicant);
            Session::flash('ic_number', $request->ic_number);
            Session::flash('contact_one_of_applicant', $request->contact_one_of_applicant);
            Session::flash('contact_two_of_applicant', $request->contact_two_of_applicant);
            Session::flash('email_of_applicant', $request->email_of_applicant);
            Session::flash('address_one', $request->address_one);
            Session::flash('address_two', $request->address_two);
            Session::flash('postcode', $request->postcode);
            Session::flash('city', $request->city);
            Session::flash('state', $request->state);
            Session::flash('country', $request->country);
            Session::flash('name_of_reference', $request->name_of_reference);
            Session::flash('contact_of_reference', $request->contact_of_reference);
            Session::flash('seller_one', $request->seller_one);
            Session::flash('seller_two', $request->seller_two);
            Session::flash('tandcitsu', $request->tandcitsu);
            Session::flash('tandcctos', $request->tandcctos);
        // END : throw back all the already validated request, so that it will be included in next request
    }

    public function submitContractForm(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'product' => 'required|exists:irs_itemmaster,IM_ID',
            'no_of_installment_month' => 'required|numeric',
            'name_of_applicant' => 'required|string|min:3|max:50',
            'ic_number' => 'required|string',
            'contact_one_of_applicant' => 'required|string|min:8|max:20',
            'contact_two_of_applicant' => 'string|min:8|max:20|nullable',
            'email_of_applicant' => 'required|email',
            'address_one' => 'required|string|min:10',
            'address_two' => 'string|min:10|nullable',
            'postcode' => 'required|string|min:4|max:10',
            'city' => 'required|exists:irs_city,CI_ID',
            'state' => 'required|exists:irs_state,ST_ID',
            'country' =>  'required|exists:irs_country,CO_ID',
            'name_of_reference' => 'string|min:3|max:50',
            'contact_of_reference' => 'string|min:8|max:20',
            'seller_one' => 'required|exists:users,id|different:seller_two',
            'seller_two' => 'exists:users,id|nullable',
            'tandcitsu' => 'required|in:1',
            'tandcctos' => 'required|in:1'
        ]);

        // if any of above validation fail 
        if ($validator->fails()){
            Session::flash('errorFormValidation', 'Display Data');
            $this->saveDataInSession($request);
            return redirect()->back()->withErrors($validator->errors());
        }

        // if all passed, check for SMS tag
        $validatorSMSTag = Validator::make($request->all(), [
            'contact_one_of_applicant' => 'required|string|min:8|max:20',
            'contact_one_sms_tag' => 'required|string|min:6|max:6',
            'contact_one_sms_verified' => "required|in:valid"
        ]);
        // if only SMS tag fail, then return

        if ($validatorSMSTag->fails()) {
            Session::flash('displaySMSTag', 'Display SMS Tag');
            $this->saveDataInSession($request);
            return redirect()->back();
        }

        // if both validation passed, then only do next step insert etc etc
        DB::beginTransaction();

        try {
            $irsSalesBranch = IrsSalesBranch::where('SB_Code', config('app.branchid'))
                                    ->select(['SB_DefaultWarehouse', 'SB_DefaultBinLocation'])
                                    ->first();
            
            $customerMasterByNRIC = CustomerMaster::where('Cust_NRIC', $request->ic_number)->whereNull('deleted_at')->get();
            $customerMasterByEmail = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->whereNull('deleted_at')->get();

            // if both not found then, create record
            if (!$customerMasterByNRIC && !$customerMasterByEmail) {
                // get custidseq running number
                $custIdSeqNumber = SystemParamDetail::where('sysparam_cd', 'CUSTIDSEQ')->select(['param_val'])->first();
                $custIdSeqNumberNew = $custIdSeqNumber->param_val + 1;

                SystemParamDetail::where('sysparam_cd', 'CUSTIDSEQ')
                    ->update(['param_val' => $custIdSeqNumberNew]);

                // get custacctcdseq running number
                $custAcctCdSeqNumber = SystemParamDetail::where('sysparam_cd', 'CUSTACCTCDSEQ')->select(['param_val'])->first();
                $custAcctCdSeqNumberNew = $custAcctCdSeqNumber->param_val + 1;

                SystemParamDetail::where('sysparam_cd', 'CUSTACCTCDSEQ')
                    ->update(['param_val' => $custAcctCdSeqNumberNew]);
                
                // get custccidseq running number
                $custCcIdSeqNumber = SystemParamDetail::where('sysparam_cd', 'CUSTCCIDSEQ')->select(['param_val'])->first();
                $custCcIdSeqNumberNew = $custCcIdSeqNumber->param_val + 1;

                SystemParamDetail::where('sysparam_cd', 'CUSTCCIDSEQ')
                    ->update(['param_val' => $custCcIdSeqNumberNew]);

                $customerMaster = CustomerMaster::create([
                    'Cust_ID' =>  $custIdSeqNumberNew,
                    'Cust_AccountCode' => $custAcctCdSeqNumberNew,
                    'Cust_Name' => $request->name_of_applicant,
                    'Cust_MainAddress1' => $request->address_one,
                    'Cust_MainAddress2' => $request->address_two,
                    'Cust_MainPostcode' => $request->postcode,
                    'Cust_MainCity' => $request->city,
                    'Cust_MainState' => $request->state,
                    'Cust_MainCountry' => $request->country,
                    'Cust_AltAddress1' => $request->address_one,
                    'Cust_AltAddress2' => $request->address_two,
                    'Cust_AltPostcode' => $request->postcode,
                    'Cust_AltCity' => $request->city,
                    'Cust_AltState' => $request->state,
                    'Cust_AltCountry' => $request->country,
                    'Cust_Phone1' => $request->contact_one_of_applicant,
                    'Cust_Email' => $request->email_of_applicant,
                    'Cust_NRIC' => $request->ic_number,
                    'CC_ID' => $custCcIdSeqNumberNew,
                    'usr_created' => Auth::user()->id
                ]);
                
            } else {
                // update by using NRIC
                if ($customerMasterByNRIC) {
                    CustomerMaster::where('Cust_NRIC', $request->ic_number)
                        ->whereNull('deleted_at')
                        ->update([
                            'Cust_Name' => $request->name_of_applicant,
                            'Cust_MainAddress1' => $request->address_one,
                            'Cust_MainAddress2' => $request->address_two,
                            'Cust_MainPostcode' => $request->postcode,
                            'Cust_MainCity' => $request->city,
                            'Cust_MainState' => $request->state,
                            'Cust_MainCountry' => $request->country,
                            'Cust_AltAddress1' => $request->address_one,
                            'Cust_AltAddress2' => $request->address_two,
                            'Cust_AltPostcode' => $request->postcode,
                            'Cust_AltCity' => $request->city,
                            'Cust_AltState' => $request->state,
                            'Cust_AltCountry' => $request->country,
                            'Cust_Phone1' => $request->contact_one_of_applicant,
                            'Cust_Email' => $request->email_of_applicant,
                            'Cust_NRIC' => $request->ic_number,
                            'usr_updated' => Auth::user()->id
                        ]);
                    $customerMaster = CustomerMaster::where('Cust_NRIC', $request->ic_number)->first();
                } else {
                    // update by using Email
                    CustomerMaster::where('Cust_Email', $request->email_of_applicant)
                        ->whereNull('deleted_at')
                        ->update([
                            'Cust_Name' => $request->name_of_applicant,
                            'Cust_MainAddress1' => $request->address_one,
                            'Cust_MainAddress2' => $request->address_two,
                            'Cust_MainPostcode' => $request->postcode,
                            'Cust_MainCity' => $request->city,
                            'Cust_MainState' => $request->state,
                            'Cust_MainCountry' => $request->country,
                            'Cust_AltAddress1' => $request->address_one,
                            'Cust_AltAddress2' => $request->address_two,
                            'Cust_AltPostcode' => $request->postcode,
                            'Cust_AltCity' => $request->city,
                            'Cust_AltState' => $request->state,
                            'Cust_AltCountry' => $request->country,
                            'Cust_Phone1' => $request->contact_one_of_applicant,
                            'Cust_Email' => $request->email_of_applicant,
                            'Cust_NRIC' => $request->ic_number,
                            'usr_updated' => Auth::user()->id
                        ]);
                    $customerMaster = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->first();

                }
            }

            // if user is a customer
            if (Auth::user()->branchind === 4) {
                $customerUserMap = CustomerUserMap::firstOrCreate(
                    [
                        'users_id' => Auth::user()->id,
                        'customer_id' => $customerMaster->id
                    ]
                );
            } 

            $irsItemMaster = IrsItemMaster::where('IM_ID', $request->product)
                ->select(['IM_Description', 'IM_Type', 'IM_BaseUOMID'])
                ->first();
            $irsItemUom = IrsItemUom::where('IU_ItemID', $request->product)
                ->select(['IU_SalesPrice2'])
                ->first();

            // START : get CNH_DocNo
            $cnrtlDocSeqNumber = SystemParamDetail::where('sysparam_cd', 'CNRTLDOCSEQ')->select(['param_val'])->first();
            
            $cnrtlDocSeqNumberNew = $cnrtlDocSeqNumber->param_val + 1;

            SystemParamDetail::where('sysparam_cd', 'CNRTLDOCSEQ')
                ->update(['param_val' => $cnrtlDocSeqNumberNew]);

            $cnrtlDocPrefix = SystemParamDetail::where('sysparam_cd', 'CNRTLDOCPREFIX')->select(['param_val'])->first();

            $cnrtlDocSeqNumberNewPadded = str_pad((string)$cnrtlDocSeqNumberNew, 8, "0", STR_PAD_LEFT);
            $cnhDocNo = "{$cnrtlDocPrefix->param_val}-{$cnrtlDocSeqNumberNewPadded}";
            // END : get CNH_DocNo

            $contractMaster = ContractMaster::create([
                'branchid' => config('app.branchid'),
                'CNH_DocNo' => $cnhDocNo,
                'CNH_CustomerID' => $customerMaster->id,
                'CNH_PostingDate' => Carbon::now(),
                'CNH_DocDate' => Carbon::now(),
                'CNH_NameRef' => $request->name_of_reference,
                'CNH_ContactRef' => $request->contact_of_reference,
                'CNH_SalesAgent1' => $request->seller_one,
                'CNH_SalesAgent1' => $request->seller_one,
                'CNH_SalesAgent2' => $request->seller_two,
                'CNH_TotInstPeriod' => $request->no_of_installment_month,
                'CNH_Total' => 1 * $irsItemUom->IU_SalesPrice2,
                'CNH_Tax' => 0,
                'CNH_Taxable_Amt' => 1 * $irsItemUom->IU_SalesPrice2,
                'CNH_InstallAddress1' => $request->address_one,
                'CNH_InstallPostCode' => $request->postcode,
                'CNH_InstallCity' => $request->city,
                'CNH_InstallState' => $request->state,
                'CNH_InstallCountry' => $request->country,
                'CNH_NetTotal' => 1 * $irsItemUom->IU_SalesPrice2,
                'CNH_TNCInd' => $request->tandcitsu,
                'CNH_CTOSInd' => $request->tandcctos,
                'CNH_SmsTag' => $request->contact_one_sms_tag,
                'CNH_EmailVerify' => (Auth::user()->branchind === 4) ? 1 : 0,
                'CNH_WarehouseID' => $irsSalesBranch->SB_DefaultWarehouse,
                'CNH_Status' => 'Pending',
                'usr_created' => Auth::user()->id
            ]);

            $cndQty = 1;
            $cndUnitPrice = $irsItemUom->IU_SalesPrice2;
            $cndSubTotal = $cndQty * $cndUnitPrice;

            $cndTaxAmt = 0;
            $cndTaxableAmt = $cndQty * $irsItemUom->IU_SalesPrice2;

            $cndTotal = $cndSubTotal + $cndTaxableAmt;

            $contractMasterDtl =ContractMasterDtl::create([
                'contractmast_id' => $contractMaster->id,
                'CND_ItemID' => $request->product,
                'CND_Description' => $irsItemMaster->IM_Description,
                'CND_ItemUOMID' => $irsItemMaster->IM_Type,
                'CND_ItemTypeID' => $irsItemMaster->IM_BaseUOMID,
                'CND_Qty' => $cndQty,
                'CND_UnitPrice' => $cndUnitPrice,
                'CND_SubTotal' => $cndSubTotal,
                'CND_TaxAmt' => $cndTaxAmt,
                'CND_TaxableAmt' => $cndTaxableAmt,
                'CND_Total' => $cndTotal,
                'CND_ItemSeq' => 1,
                'CND_WarehouseID' => $irsSalesBranch->SB_DefaultWarehouse,
                'CND_BinLocationID' => $irsSalesBranch->SB_DefaultBinLocation,
                'usr_created' => Auth::user()->id
            ]);

            // $contractMasterAttachment = ContractMasterAttachment::create([
            //     'contractmast_id' => $contractMaster->id,
                // 'icno_file' => '',
                // 'icno_mime' => '',
                // 'icno_size' => '',
                // 'income_file' => '',
                // 'income_mime' => '',
                // 'income_size' => '',
                // 'bankstatement_file' => '',
                // 'bankstatement_mime' => '',
                // 'bankstatement_size' => '',
                // 'comp_form_file' => '',
                // 'comp_form_mime' => '',
                // 'comp_form_size' => '',
                // 'comp_icno_file' => '',
                // 'comp_icno_mime' => '',
                // 'comp_icno_size' => '',
                // 'comp_bankstatement_file' => '',
                // 'comp_bankstatement_mime' => '',
                // 'comp_bankstatement_size' => '',
            // ]);

            // Call CTOS API : To be Confirmed
            $ctosRes = false;

            // based on fail or successful API call
            $contractMaster->update([
                'CTOS_verify' => ($ctosRes) ? 1 : 0,
                'CTOS_Score' => ($ctosRes) ? 100 : null
            ]);
          
            $cnsoLogSeqNumber = SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')->select(['param_val'])->first();
            $cnsoLogSeqNumberNew = $cnsoLogSeqNumber->param_val + 1;

            SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')
                ->update(['param_val' => $cnsoLogSeqNumberNew]);

            $contractMasterLog = ContractMasterLog::create([
                'rcd_grp' => $cnsoLogSeqNumberNew,
                'action' => 'ADD',
                'trx_type' => 'CNSO',
                'subtrx_type' => '',
                'contractmast_id' => $contractMaster->id,
                'branchid' => $contractMaster->branchid,
                'CNH_DocNo' => $contractMaster->CNH_DocNo,
                'CNH_CustomerID' => $contractMaster->CNH_Customer_ID,
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
                'CNH_CommissionStartDate' => $contractMaster->CNH_CommissionStartDate,
                'contractmastdtl_id' => $contractMasterDtl->id,
                'CND_ItemID' => $contractMasterDtl->CND_ItemID,
                'CND_Description' => $contractMasterDtl->CND_Description,
                'CND_ItemUOMID' => $contractMasterDtl->CND_ItemUOMID,
                'CND_ItemTypeID' => $contractMasterDtl->CND_ItemTypeID,
                'CND_Qty' => $contractMasterDtl->CND_Qty,
                'CND_UnitPrice' => $contractMasterDtl->CND_UnitPrice,
                'CND_SubTotal' => $contractMasterDtl->CND_SubTotal,
                'CND_TaxAmt' => $contractMasterDtl->CND_TaxAmt,
                'CND_TaxableAmt' => $contractMasterDtl->CND_TaxableAmt,
                'CND_Total' => $contractMasterDtl->CND_Total,
                'CND_SerialNo' => $contractMasterDtl->CND_SerialNo,
                'CND_ItemSeq' => $contractMasterDtl->CND_ItemSeq,
                'CND_WarehouseID' => $contractMasterDtl->CND_WarehouseID,
                'CND_BinLocationID' => $contractMasterDtl->CND_BinLocationID,
                'cndeliveryorder_id' => $contractMasterDtl->cndeliveryorder_id,
                'usr_created' => Auth::user()->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
