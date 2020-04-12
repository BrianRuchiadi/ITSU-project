<?php
namespace App\Http\Controllers\Customer;

use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Hashids\Hashids;
use Carbon\Carbon;
use Session;
use DB;

use App\Models\IrsSalesBranch;
use App\Models\IrsItemMaster;
use App\Models\CustomerMaster;
use App\Models\CustomerUserMap;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\ContractDeliveryOrder;
use App\Models\ContractInvoice;
use App\Models\ContractInvoiceDtl;
use App\Models\SystemParamDetail;
use App\Models\ContractMasterAttachment;
use App\Models\ContractMasterLog;
use App\Models\IrsItemRentalOpt;

use App\Models\IrsCity;
use App\Models\IrsState;
use App\Models\IrsCountry;
use App\Models\User;


class CustomerController extends Controller
{
    public function getReferralLink(Request $request) {
        $hashids = new Hashids(config('app.salt'), 5);
        $hashedId = $hashids->encode(Auth::user()->id);
        
        return [
            'url' => config('app.url') . '/login?ref=' . $hashedId
        ];
    }

    public function submitContractForm(Request $request) {
        $validator = Validator::make($request->all(), [
            'product' => 'required|exists:irs_itemmaster,IM_ID',
            'no_of_installment_month' => 'required|numeric',
            'name_of_applicant' => 'required|string|min:3|max:50',
            'ic_number' => 'required|string',
            'tel_code_1' => 'required|string',
            'contact_one_of_applicant' => 'required|string',
            'tel_code_2' => 'required_with:contract_two_of_applicant|string|nullable',
            'contact_two_of_applicant' => 'required_with:tel_code_2|string|min:8|max:20|nullable',
            'email_of_applicant' => 'required|email',
            'address_one' => 'required|string|min:10',
            'address_two' => 'string|min:10|nullable',
            'postcode' => 'required|string|min:4|max:10',
            'city' => 'required|exists:irs_city,CI_ID',
            'state' => 'required|exists:irs_state,ST_ID',
            'country' =>  'required|exists:irs_country,CO_ID',
            'name_of_reference' => 'nullable|string|min:3|max:50',
            'contact_of_reference' => 'nullable|string|min:8|max:20',
            'seller_one' => 'exists:users,id|nullable|different:seller_two',
            'seller_two' => 'exists:users,id|nullable',
            'tandcitsu' => 'required|in:1',
            'tandcctos' => 'required|in:1',
            'applicant_type' => 'required|in:individual_applicant,self_employed',
            'file_inclusion' => 'required|string|in:include,exclude',
        ]);

        // START : Validate Email Exists
        $userEmailValidation = User::where('email', $request->email_of_applicant)->exists();
        $customerMasterEmailValidation = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->exists();

        $sendEmail = ($userEmailValidation || $customerMasterEmailValidation) ? 'no' : 'yes';
        // END : Validate Email Exists

        // START : File Validation 
        $hasFileValidation = ($request->file_inclusion == 'include') ? true : false;

        if ($hasFileValidation) {
            if ($request->applicant_type == 'individual_applicant') {
                $validatorFile = Validator::make($request->all(), [
                    'file_individual_icno' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    'file_individual_income' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    'file_individual_bankstatement' => 'file|required|mimes:jpeg,png,jpg,pdf',
                ]);
            } else if ($request->applicant_type == 'self_employed') {
                $validatorFile = Validator::make($request->all(), [
                    'file_company_form' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    'file_company_icno' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    'file_company_bankstatement' => 'file|required|mimes:jpeg,png,jpg,pdf',
                ]);
            }

            if ($validatorFile->fails()) {
                Session::flash('errorFormValidation', 'Display Data');
                $this->saveDataInSession($request);
                return redirect()->back()->withErrors($validatorFile->errors());
            }
        }
        // END : File Validation
        if (!$request->contact_one_sms_verified) {
            $this->saveTemporarilyUploadedFile($request);
        }

        // if any of above validation fail 
        if ($validator->fails()){
            Session::flash('errorFormValidation', 'Display Data');
            $this->saveDataInSession($request);
            return redirect()->back()->withErrors($validator->errors());
        }        

        // check for SMS tag
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
            if (!$customerMasterByNRIC->count() && !$customerMasterByEmail->count()) {
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

                $custAcctCdPadded = str_pad((string)$custAcctCdSeqNumberNew, 6, "0", STR_PAD_LEFT);

                $customerMaster = CustomerMaster::create([
                    'Cust_ID' =>  $custIdSeqNumberNew,
                    'Cust_AccountCode' => $custAcctCdPadded,
                    'Cust_NAME' => $request->name_of_applicant,
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
                    'Cust_Phone2' => $request->contact_two_of_applicant,
                    'Cust_Email' => $request->email_of_applicant,
                    'Cust_NRIC' => $request->ic_number,
                    'telcode1' => $request->tel_code_1,
                    'telcode2' => $request->tel_code_2,
                    'CC_ID' => $custCcIdSeqNumberNew,
                    'usr_created' => Auth::user()->id
                ]);
            } else {
                // update by using NRIC
                if ($customerMasterByNRIC->count()) {
                    CustomerMaster::where('Cust_NRIC', $request->ic_number)
                        ->whereNull('deleted_at')
                        ->update([
                            'Cust_NAME' => $request->name_of_applicant,
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
                            'Cust_Phone2' => $request->contact_two_of_applicant,
                            'Cust_Email' => $request->email_of_applicant,
                            'Cust_NRIC' => $request->ic_number,
                            'telcode1' => $request->tel_code_1,
                            'telcode2' => $request->tel_code_2,
                            'usr_updated' => Auth::user()->id
                        ]);
                    $customerMaster = CustomerMaster::where('Cust_NRIC', $request->ic_number)->first();
                } else {
                    // update by using Email
                    CustomerMaster::where('Cust_Email', $request->email_of_applicant)
                        ->whereNull('deleted_at')
                        ->update([
                            'Cust_NAME' => $request->name_of_applicant,
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
                            'Cust_Phone2' => $request->contact_two_of_applicant,
                            'Cust_Email' => $request->email_of_applicant,
                            'Cust_NRIC' => $request->ic_number,
                            'telcode1' => $request->tel_code_1,
                            'telcode2' => $request->tel_code_2,
                            'usr_updated' => Auth::user()->id
                        ]);
                    $customerMaster = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->first();

                }
            }
            // if user is a customer
            if (Auth::user()->branchind == 4) {
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

            $irsItemRental = IrsItemRentalOpt::where('IR_ItemID', $request->product)
                ->where('IR_OptionKey', $request->no_of_installment_month)
                ->select(['IR_UnitPrice'])->first();

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
                'CNH_Total' => 1 * $irsItemRental->IR_UnitPrice,
                'CNH_Tax' => 0,
                'CNH_TaxableAmt' => 1 * $irsItemRental->IR_UnitPrice,
                'CNH_NetTotal' => 1 * $irsItemRental->IR_UnitPrice,
                'CNH_Address1' => $request->address_one,
                'CNH_Address2' => $request->address_two,
                'CNH_Postcode' => $request->postcode,
                'CNH_City' => $request->city,
                'CNH_State' => $request->state,
                'CNH_Country' => $request->country,
                'CNH_InstallAddress1' => $request->address_one,
                'CNH_InstallAddress2' => $request->address_two,
                'CNH_InstallPostcode' => $request->postcode,
                'CNH_InstallCity' => $request->city,
                'CNH_InstallState' => $request->state,
                'CNH_InstallCountry' => $request->country,
                'CNH_TNCInd' => $request->tandcitsu,
                'CNH_CTOSInd' => $request->tandcctos,
                'CNH_SmsTag' => $request->contact_one_sms_tag,
                'CNH_EmailVerify' => ( $sendEmail == 'yes') ? 0 : 1,
                'do_complete_ind' => 0,
                'CNH_WarehouseID' => $irsSalesBranch->SB_DefaultWarehouse,
                'CNH_Status' => 'Pending',
                'usr_created' => Auth::user()->id
            ]);

            $cndQty = 1;
            $cndUnitPrice = $irsItemRental->IR_UnitPrice;
            $cndSubTotal = $cndQty * $cndUnitPrice;

            $cndTaxAmt = 0;
            $cndTaxableAmt = $cndQty * $irsItemRental->IR_UnitPrice;

            $cndTotal = $cndSubTotal + $cndTaxAmt;

            $contractMasterDtl = ContractMasterDtl::create([
                'contractmast_id' => $contractMaster->id,
                'CND_ItemID' => $request->product,
                'CND_Description' => $irsItemMaster->IM_Description,
                'CND_ItemUOMID' => $irsItemMaster->IM_BaseUOMID,
                'CND_ItemTypeID' => $irsItemMaster->IM_Type,
                'CND_Qty' => $cndQty,
                'CND_UnitPrice' => $cndUnitPrice,
                'CND_SubTotal' => $cndSubTotal,
                'CND_TaxAmt' => $cndTaxAmt,
                'CND_TaxableAmt' => $cndTaxableAmt,
                'CND_Total' => $cndTotal,
                'CND_ItemSeq' => 1,
                'CND_WarehouseID' => $irsSalesBranch->SB_DefaultWarehouse,
                'CND_BinLocationID' => $irsSalesBranch->SB_DefaultBinLocation,
                'cndeliveryorder_id' => null,
                'usr_created' => Auth::user()->id
            ]);

            $contractMasterAttachment = ContractMasterAttachment::create([
                'contractmast_id' => $contractMaster->id,
                'icno_file' => ($request->applicant_type == 'individual_applicant') ? 
                    $this->getFileBlob('individual_applicant' ,'icno') : null,
                'icno_mime' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_icno_mime') : null,
                'icno_size' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_icno_size') : null,

                'income_file' => ($request->applicant_type == 'individual_applicant') ? 
                    $this->getFileBlob('individual_applicant' ,'income') : null,
                'income_mime' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_income_mime') : null,
                'income_size' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_income_size') : null,
            
                'bankstatement_file' => ($request->applicant_type == 'individual_applicant') ? 
                    $this->getFileBlob('individual_applicant' ,'bankstatement') : null,
                'bankstatement_mime' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_bankstatement_mime') : null,
                'bankstatement_size' => ($request->applicant_type == 'individual_applicant') ? 
                    Session::get('file_temp_individual_bankstatement_size') : null,

                'comp_form_file' => ($request->applicant_type == 'self_employed') ? 
                    $this->getFileBlob('self_employed' ,'form') : null,
                'comp_form_mime' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_form_mime') : null,
                'comp_form_size' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_form_size') : null,

                'comp_icno_file' => ($request->applicant_type == 'self_employed') ? 
                    $this->getFileBlob('self_employed' ,'icno') : null,
                'comp_icno_mime' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_icno_mime') : null,
                'comp_icno_size' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_icno_size') : null,

                'comp_bankstatement_file' => ($request->applicant_type == 'self_employed') ? 
                    $this->getFileBlob('self_employed' ,'bankstatement') : null,
                'comp_bankstatement_mime' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_bankstatement_mime') : null,
                'comp_bankstatement_size' => ($request->applicant_type == 'self_employed') ? 
                    Session::get('file_temp_company_bankstatement_size') : null,
            ]);
            // remove all the temp session, and temp attachment
            $this->removeTempAttachment($request);
            $this->flushAllFileTempSession($request);

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

            if ($sendEmail == 'yes') {
                $hashids = new Hashids(config('app.salt'), 5);
                $contractIdEncode = $hashids->encode($contractMaster->id);

                $urlLink = URL::signedRoute(
                    'contract.email.verify', ['id' => $contractIdEncode]
                );

                $data = [
                    'title' => 'Email verification for ITSU Kubikt',
                    'content' => 'Click link to verify email for contract application. ',
                    'link' => $urlLink,
                    'warning' => ''
                ];
                Mail::send('page.auth.email', $data, function($message) use ($request) {
                    $message->to($request['email_of_applicant'], $request['name_of_applicant'])->subject('Hy, ' . $request['name_of_applicant']);
                });

                Session::flash('showSuccessMessage', 'Successfully submitted application form, Email has been sent to verify contract email');
            } else {
                Session::flash('showSuccessMessage', 'Successfully submitted application form');
            }

            DB::commit();

            return [
                'status' => 'success'
            ];
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function flushAllFileTempSession(Request $request) {
        foreach ($request->session()->all() as $key => $val) {
            if (strpos($key, 'file_temp') !== false) {
                // remove the related file
                $request->session()->forget($key);
            }
        }
    }

    public function removeTempAttachment(Request $request) {
        if ($request->applicant_type == 'individual_applicant') {
            Storage::delete('_temp/' . Session::get('file_temp_individual_icno_file') . '.' . Session::get('file_temp_individual_icno_extension'));
            Storage::delete('_temp/' . Session::get('file_temp_individual_income_file') . '.' . Session::get('file_temp_individual_income_extension'));
            Storage::delete('_temp/' . Session::get('file_temp_individual_bankstatement_file') . '.' . Session::get('file_temp_individual_bankstatement_extension'));
        } else if ($request->applicant_type == 'self_employed') {
            Storage::delete('_temp/' . Session::get('file_temp_company_icno_file') . '.' . Session::get('file_temp_company_icno_extension'));
            Storage::delete('_temp/' . Session::get('file_temp_company_form_file') . '.' . Session::get('file_temp_company_form_extension'));
            Storage::delete('_temp/' . Session::get('file_temp_company_bankstatement_file') . '.' . Session::get('file_temp_company_bankstatement_extension'));
        }
    }

    public function getFileBlob($type, $field) {
        $userId = Auth::user()->id;
        $baseStoragePath = storage_path('app/public/_temp');

        if ($type == 'individual_applicant') {
            switch ($field) {
                case 'icno':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_individual_icno_file') . 
                            "." . Session::get('file_temp_individual_icno_extension')
                        )
                    );
                case 'income':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_individual_income_file') . 
                            "." . Session::get('file_temp_individual_income_extension')
                        )
                    );
                case 'bankstatement':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_individual_bankstatement_file') . 
                            "." . Session::get('file_temp_individual_bankstatement_extension')
                        )
                    );
            }
        } else if ($type == 'self_employed') {
            switch ($field) {
                case 'icno':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_company_icno_file') . 
                            "." . Session::get('file_temp_company_icno_extension')
                        )
                    );
                case 'form':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_company_form_file') . 
                            "." . Session::get('file_temp_company_form_extension')
                        )
                    );
                case 'bankstatement':
                    return base64_encode(
                        file_get_contents(
                            "{$baseStoragePath}/" . Session::get('file_temp_company_bankstatement_file') . 
                            "." . Session::get('file_temp_company_bankstatement_extension')
                        )
                    );
            }
        }
    }

    public function saveTemporarilyUploadedFile(Request $request) {
        Session::flash('file_temp_temporary_saved', true);

        if ($request->applicant_type == 'individual_applicant') {
            // $request->file_individual_icno
            if ($request->file('file_individual_icno')) {     
                $request->file('file_individual_icno')->storeAs(
                    '_temp', 
                    'file_individual_icno_' . Auth::user()->id .
                    '.' . $request->file('file_individual_icno')->extension() // extension
                );
    
                Session::put('file_temp_individual_icno_file', 'file_individual_icno_' . Auth::user()->id);
                Session::put('file_temp_individual_icno_mime', $request->file('file_individual_icno')->getClientMimeType());
                Session::put('file_temp_individual_icno_size', $request->file('file_individual_icno')->getSize());
                Session::put('file_temp_individual_icno_extension', $request->file('file_individual_icno')->extension());
            }
            // $request->file_individual_income
            if ($request->file('file_individual_income')) {
                $request->file('file_individual_income')->storeAs(
                    '_temp', 
                    'file_individual_income_' . Auth::user()->id .
                    '.' . $request->file('file_individual_income')->extension()
                );
    
                Session::put('file_temp_individual_income_file', 'file_individual_income_' . Auth::user()->id);
                Session::put('file_temp_individual_income_mime', $request->file('file_individual_income')->getClientMimeType());
                Session::put('file_temp_individual_income_size', $request->file('file_individual_income')->getSize());
                Session::put('file_temp_individual_income_extension', $request->file('file_individual_income')->extension());
            }

            // $request->file_individual_bankstatement
            if ($request->file('file_individual_bankstatement')) {
                $request->file('file_individual_bankstatement')->storeAs(
                    '_temp', 
                    'file_individual_bankstatement_' . Auth::user()->id .
                    '.' . $request->file('file_individual_bankstatement')->extension()
                );
    
                Session::put('file_temp_individual_bankstatement_file', 'file_individual_bankstatement_' . Auth::user()->id);
                Session::put('file_temp_individual_bankstatement_mime', $request->file('file_individual_bankstatement')->getClientMimeType());
                Session::put('file_temp_individual_bankstatement_size', $request->file('file_individual_bankstatement')->getSize());
                Session::put('file_temp_individual_bankstatement_extension', $request->file('file_individual_bankstatement')->extension());
            }

            // $base64 = base64_encode(file_get_contents($request->file_individual_bankstatement->path()));
            // Session::put('file_temp_individual_bankstatement_base64', $base64);
        } else if ($request->applicant_type == 'self_employed') {
            // $request->file_company_icno
            if ($request->file('file_company_icno')) {
                $request->file('file_company_icno')->storeAs(
                    '_temp', 
                    'file_company_icno_' . Auth::user()->id . 
                    '.' . $request->file('file_company_icno')->extension()
                );
    
                Session::put('file_temp_company_icno_file', 'file_company_icno_' . Auth::user()->id);
                Session::put('file_temp_company_icno_mime', $request->file('file_company_icno')->getClientMimeType());
                Session::put('file_temp_company_icno_size', $request->file('file_company_icno')->getSize());
                Session::put('file_temp_company_icno_extension', $request->file('file_company_icno')->extension());
            }

            // $request->file_company_form
            if ($request->file('file_company_form')) {
                $request->file('file_company_form')->storeAs(
                    '_temp',
                    'file_company_form_' . Auth::user()->id . 
                    '.' . $request->file('file_company_form')->extension()
                );
    
                Session::put('file_temp_company_form_file', 'file_company_form_' . Auth::user()->id);
                Session::put('file_temp_company_form_mime', $request->file('file_company_form')->getClientMimeType());
                Session::put('file_temp_company_form_size', $request->file('file_company_form')->getSize());
                Session::put('file_temp_company_form_extension', $request->file('file_company_form')->extension());
            }

            // $request->file_company_bankstatement
            if ($request->file('file_company_bankstatement')) {
                $request->file('file_company_bankstatement')->storeAs(
                    '_temp', 
                    'file_company_bankstatement_' . Auth::user()->id . 
                    '.' . $request->file('file_company_bankstatement')->extension()
                );
    
                Session::put('file_temp_company_bankstatement_file', 'file_company_bankstatement_' . Auth::user()->id);
                Session::put('file_temp_company_bankstatement_mime', $request->file('file_company_bankstatement')->getClientMimeType());
                Session::put('file_temp_company_bankstatement_size', $request->file('file_company_bankstatement')->getSize());
                Session::put('file_temp_company_bankstatement_extension', $request->file('file_company_bankstatement')->extension());
            }
        }
    }

    public function saveDataInSession($request) {
        // START : throw back all the already validated request, so that it will be included in next request
        Session::flash('product', $request->product);
        Session::flash('no_of_installment_month', $request->no_of_installment_month);
        Session::flash('unit_price', $request->unit_price);
        Session::flash('name_of_applicant', $request->name_of_applicant);
        Session::flash('ic_number', $request->ic_number);
        Session::flash('tel_code_options_1', $request->tel_code_1);
        Session::flash('contact_one_of_applicant', $request->contact_one_of_applicant);
        Session::flash('tel_code_options_2', $request->tel_code_2);
        Session::flash('contact_two_of_applicant', $request->contact_two_of_applicant);
        Session::flash('email_of_applicant', $request->email_of_applicant);
        Session::flash('address_one', $request->address_one);
        Session::flash('address_two', $request->address_two);
        Session::flash('postcode', $request->postcode);
        Session::flash('city', $request->city);
        Session::flash('state', $request->state);
        Session::flash('country', $request->country);
        Session::flash('name_of_reference', $request->name_of_reference);
        Session::flash('applicant_type', $request->applicant_type);
        Session::flash('contact_of_reference', $request->contact_of_reference);
        Session::flash('seller_one', $request->seller_one);
        Session::flash('seller_two', $request->seller_two);
        Session::flash('tandcitsu', $request->tandcitsu);
        Session::flash('tandcctos', $request->tandcctos);
        Session::flash('file_individual_icno', ($request->file_individual_icno) ? 1 : null);
        Session::flash('file_individual_income', ($request->file_individual_income) ? 1 : null);
        Session::flash('file_individual_bankstatement', ($request->file_individual_bankstatement) ? 1 : null);
        Session::flash('file_company_form', ($request->file_company_form) ? 1 : null);
        Session::flash('file_company_icno', ($request->file_company_icno) ? 1 : null);
        Session::flash('file_company_bankstatement', ($request->file_company_bankstatement) ? 1 : null);
        
    // END : throw back all the already validated request, so that it will be included in next request
    }

    public function showCustomerContractList() {
        if (Auth::user()->branchind == 4) {
            $userMap = CustomerUserMap::where('users_id', Auth::user()->id)->get();
            $userMapCustomer = collect($userMap)->pluck('customer_id')->toArray();
            $contracts = DB::table('customermaster')
                           ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                           ->whereIn('CNH_CustomerID', $userMapCustomer)->select([
                            'contractmaster.id',
                            'contractmaster.CNH_PostingDate',
                            'contractmaster.CNH_TotInstPeriod',
                            'contractmaster.CNH_DocNo',
                            'customermaster.Cust_NAME',
                            'contractmaster.CNH_Status'
                        ])->paginate(30);
            $contractsIds = collect($contracts->items())->pluck('id')->toArray();

            $invoices = (count($contractsIds)) ?
                ContractInvoice::whereIn('contractmast_id', $contractsIds)->get() :
                [];
            // delivery orders no need to display for customer
            $deliveryOrders = [];
        } else {
            $contracts = [];
            $invoices = [];
            $deliveryOrders = [];
        }

        $user = Auth::user();
        return view('page.customer.contract-list', [
            'invoices' => $invoices,
            'contracts' => $contracts,
            'user' => $user,
            'error_message' => '',
            'delivery_orders' => $deliveryOrders
        ]);
    }

    public function showSearchResult(Request $request) {  
        $validator = Validator::make($request->all(), [
            'customer' => 'string|nullable|min:4|required_without_all:ic_no,tel_no,contract_no',
            'ic_no' => 'string|nullable|min:6',
            'tel_no' => 'string|nullable|min:4',
            'contract_no' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            return view('page.customer.contract-list', [
                'invoices' => [],
                'contracts' => [],
                'delivery_orders' => [],
                'user' => Auth::user(),
                'error_message' => $validator->errors()->first()
            ]);
        }

        $params = $request->all();

        // another validation
        if ($request->contract_no) {
            // validation and data shaping
            if (is_numeric($request->contract_no)) {
                $validator = Validator::make($request->all(), ['contract_no' => 'string|min:2']);
                $errMessage = "Contract No must have at least 2 value";

                $params['contract_no'] = str_pad($request->contract_no, 8, '0', STR_PAD_LEFT);
                $params['contract_no_type'] = 'not-strict';
            } else {
                $validator = Validator::make($request->all(), ['contract_no' => 'string|min:4']);
                $errMessage = "Contract No must have at least 4 characters";

                $params['contract_no'] = $params['contract_no'];
                $params['contract_no_type'] = 'strict';
            }

            if ($validator->fails()) {
                return view('page.customer.contract-list', [
                    'invoices' => [],
                    'contracts' => [],
                    'delivery_orders' => [],
                    'user' => Auth::user(),
                    'error_message' => $errMessage
                ]);
            }
        }

        $contracts = DB::table('customermaster')
                       ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID');

        $contracts = (!empty($request->customer)) ? $contracts->where('customermaster.Cust_NAME', 'like', '%' . $request->customer . '%') : $contracts; 
        $contracts = (!empty($request->ic_no)) ? $contracts->where('customermaster.Cust_NRIC', 'like', '%' . $request->ic_no . '%') : $contracts; 
        $contracts = (!empty($request->tel_no)) ? $contracts->where('customermaster.Cust_Phone1', 'like', '%' . $request->tel_no . '%') : $contracts; 

        if ($request->contract_no) {
            $contracts = ($params['contract_no_type'] == 'not-strict') ? 
                $contracts->where('contractmaster.CNH_DocNo', 'like', '%' . $params['contract_no'] . '%') : 
                $contracts->where('contractmaster.CNH_DocNo', '=', $params['contract_no']); 
        }

        $contracts = $contracts->select([
            'contractmaster.id',
            'contractmaster.CNH_PostingDate',
            'contractmaster.CNH_TotInstPeriod',
            'contractmaster.CNH_DocNo',
            'customermaster.Cust_NAME',
            'contractmaster.CNH_Status'
        ])->paginate(30);

        $contractsIds = collect($contracts->items())->pluck('id')->toArray();

        $invoices = (count($contractsIds)) ?
            ContractInvoice::whereIn('contractmast_id', $contractsIds)->get() :
            [];
        $deliveryOrders = (count($contractsIds)) ? 
            ContractDeliveryOrder::whereIn('contractmast_id',$contractsIds)->get() :
            [];

        $user = Auth::user();

        return view('page.customer.contract-list', [
            'contracts' => $contracts,
            'user' => $user,
            'delivery_orders' => $deliveryOrders,
            'error_message' => '',
            'invoices' => $invoices
        ]);
    }

    public function showCustomerContractDetail($contractId) {
        $contractDetails = DB::table('customermaster')
                            ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                            ->join('contractmasterdtl', 'contractmaster.id', '=', 'contractmasterdtl.contractmast_id')
                            ->where('contractmaster.id', '=', $contractId)
                            ->first();

        $contractDetails->Apply_Date = Carbon::parse($contractDetails->CNH_DocDate)->format('d/m/Y H:i:s');
        $contractDetails->Approve_Date = Carbon::parse($contractDetails->CNH_ApproveDate)->format('d/m/Y H:i:s');
        $contractDetails->Reject_Date = Carbon::parse($contractDetails->CNH_RejectDate)->format('d/m/Y H:i:s');

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

        return view('page.customer.contract-detail', compact('contractDetails', 'itemMaster', 'city', 'state', 'country', 'agent1', 'agent2', 'attachment'));
    }

    public function showResubmitForm($contractId) {

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
                                'contractmaster.CTOS_Score',
                                'contractmaster.CNH_DocDate',
                                'contractmaster.CNH_NameRef',
                                'contractmaster.CNH_ContactRef',
                                'contractmaster.CNH_RejectDate',
                                'contractmaster.CNH_RejectDesc',
                                'contractmaster.CNH_Status',
                                'contractmaster.CNH_Address1',
                                'contractmaster.CNH_Address2',
                                'contractmaster.CNH_Postcode',
                                'contractmaster.CNH_City',
                                'contractmaster.CNH_State',
                                'contractmaster.CNH_Country',
                                'contractmasterdtl.CND_ItemID',
                                'contractmasterdtl.CND_UnitPrice',
                                'customermaster.id as customer_id',
                                'customermaster.Cust_NAME',
                                'customermaster.Cust_NRIC',
                                'customermaster.Cust_Phone1',
                                'customermaster.Cust_Phone2',
                                'customermaster.Cust_Email',
                                'customermaster.telcode1',
                                'customermaster.telcode2',
                            ])->first();

        $attachment = ContractMasterAttachment::where('contractmast_id', $contractId)->first();

        $attachment->type = ($attachment->icno_file) ? 'individual_applicant' : 'self_employed';

        return view('page.customer.resubmit-application', compact('contractDetails', 'attachment'));

    }

    public function resubmitContractForm(Request $request, $contractId) {

        $validator = Validator::make($request->all(), [
            'product' => 'required|exists:irs_itemmaster,IM_ID',
            'no_of_installment_month' => 'required|numeric',
            'name_of_applicant' => 'required|string|min:3|max:50',
            'ic_number' => 'required|string',
            'tel_code_1' => 'required|string',
            'contact_one_of_applicant' => 'required|string',
            'tel_code_2' => 'required_with:contract_two_of_applicant|string|nullable',
            'contact_two_of_applicant' => 'required_with:tel_code_2|string|min:8|max:20|nullable',
            'email_of_applicant' => 'required|email',
            'address_one' => 'required|string|min:10',
            'address_two' => 'string|min:10|nullable',
            'postcode' => 'required|string|min:4|max:10',
            'city' => 'required|exists:irs_city,CI_ID',
            'state' => 'required|exists:irs_state,ST_ID',
            'country' =>  'required|exists:irs_country,CO_ID',
            'name_of_reference' => 'nullable|string|min:3|max:50',
            'contact_of_reference' => 'nullable|string|min:8|max:20',
            'seller_one' => 'exists:users,id|nullable|different:seller_two',
            'seller_two' => 'exists:users,id|nullable',
            'tandcitsu' => 'required|in:1',
            'tandcctos' => 'required|in:1',
            'applicant_type' => 'required|in:individual_applicant,self_employed',
            'file_inclusion' => 'required|string|in:include,exclude',
            'previous_applicant_type' => 'required|string|in:individual_applicant,self_employed',
        ]);

        // START : Validate Email Exists
        $userEmailValidation = User::where('email', $request->email_of_applicant)->exists();
        $customerMasterEmailValidation = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->exists();

        $sendEmail = ($userEmailValidation || $customerMasterEmailValidation) ? 'no' : 'yes';
        // END : Validate Email Exists

        // START : File Validation 
        $hasFileValidation = ($request->file_inclusion == 'include') ? true : false;

        if ($hasFileValidation) {
            if ($request->applicant_type == 'individual_applicant') {
                if ($request->previous_applicant_type == 'individual_applicant') {
                    $validatorFile = Validator::make($request->all(), [
                        'file_individual_icno' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                        'file_individual_income' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                        'file_individual_bankstatement' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                    ]);
                } else {
                    $validatorFile = Validator::make($request->all(), [
                        'file_individual_icno' => 'file|required|mimes:jpeg,png,jpg,pdf',
                        'file_individual_income' => 'file|required|mimes:jpeg,png,jpg,pdf',
                        'file_individual_bankstatement' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    ]);
                }
            } else if ($request->applicant_type == 'self_employed') {
                if ($request->previous_applicant_type == 'self_employed') {
                    $validatorFile = Validator::make($request->all(), [
                        'file_company_form' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                        'file_company_icno' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                        'file_company_bankstatement' => 'file|nullable|mimes:jpeg,png,jpg,pdf',
                    ]);
                } else {
                    $validatorFile = Validator::make($request->all(), [
                        'file_company_form' => 'file|required|mimes:jpeg,png,jpg,pdf',
                        'file_company_icno' => 'file|required|mimes:jpeg,png,jpg,pdf',
                        'file_company_bankstatement' => 'file|required|mimes:jpeg,png,jpg,pdf',
                    ]);
                }
            }

            if ($validatorFile->fails()) {
                Session::flash('errorFormValidation', 'Display Data');
                $this->saveDataInSession($request);
                Session::flash('previous_applicant_type', $request->previous_applicant_type);
                return redirect()->back()->withErrors($validatorFile->errors());
            }
        }

        // END : File Validation
        if (!$request->contact_one_sms_verified) {
            $this->saveTemporarilyUploadedFile($request);
        }

        // if any of above validation fail 
        if ($validator->fails()){
            Session::flash('errorFormValidation', 'Display Data');
            $this->saveDataInSession($request);
            Session::flash('previous_applicant_type', $request->previous_applicant_type);
            return redirect()->back()->withErrors($validator->errors());
        }        

        // check for SMS tag
        $validatorSMSTag = Validator::make($request->all(), [
            'contact_one_of_applicant' => 'required|string|min:8|max:20',
            'contact_one_sms_tag' => 'required|string|min:6|max:6',
            'contact_one_sms_verified' => "required|in:valid"
        ]);

        // if only SMS tag fail, then return
        if ($validatorSMSTag->fails()) {
            Session::flash('displaySMSTag', 'Display SMS Tag');

            $this->saveDataInSession($request);
            Session::flash('previous_applicant_type', $request->previous_applicant_type);
            return redirect()->back();
        }

        // if both validation passed, then only do next step insert etc etc
        DB::beginTransaction();
        try {
            $contractMaster = ContractMaster::where('id', $contractId)->first();

            $customerMaster = CustomerMaster::where('id', $contractMaster->CNH_CustomerID)->update([
                'Cust_NAME' => $request->name_of_applicant,
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
                'Cust_Phone2' => $request->contact_two_of_applicant,
                'Cust_Email' => $request->email_of_applicant,
                'Cust_NRIC' => $request->ic_number,
                'telcode1' => $request->tel_code_1,
                'telcode2' => $request->tel_code_2,
                'usr_updated' => Auth::user()->id,
            ]);

            $contractMaster = ContractMaster::where('id', $contractId)->update([
                'CNH_NameRef' => $request->name_of_reference,
                'CNH_ContactRef' => $request->contact_of_reference,
                'CNH_SalesAgent1' => $request->seller_one,
                'CNH_SalesAgent2' => $request->seller_two,
                'CNH_TotInstPeriod' => $request->no_of_installment_month,
                'CNH_Total' => 1 * $request->unit_price,
                'CNH_Tax' => 0,
                'CNH_TaxableAmt' => 1 * $request->unit_price,
                'CNH_NetTotal' => 1 * $request->unit_price,
                'CNH_Address1' => $request->address_one,
                'CNH_Address2' => $request->address_two,
                'CNH_Postcode' => $request->postcode,
                'CNH_City' => $request->city,
                'CNH_State' => $request->state,
                'CNH_Country' => $request->country,
                'CNH_Status' => 'Pending',
                'usr_updated' => Auth::user()->id,
            ]);

            $irsItemMaster = IrsItemMaster::where('IM_ID', $request->product)->first();

            $cndQty = 1;
            $cndUnitPrice = $request->unit_price;
            $cndSubTotal = $cndQty * $cndUnitPrice;

            $cndTaxAmt = 0;
            $cndTaxableAmt = $cndQty * $cndUnitPrice;

            $cndTotal = $cndSubTotal + $cndTaxAmt;

            $contractMasterDtl = ContractMasterDtl::where('contractmast_id', $contractId)->update([
                'CND_ItemID' => $irsItemMaster->IM_ID,
                'CND_Description' => $irsItemMaster->IM_Description,
                'CND_ItemUOMID' => $irsItemMaster->IM_BaseUOMID,
                'CND_ItemTypeID' => $irsItemMaster->IM_Type,
                'CND_Qty' => $cndQty,
                'CND_UnitPrice' => $cndUnitPrice,
                'CND_SubTotal' => 1 * $cndSubTotal,
                'CND_TaxAmt' => $cndTaxAmt,
                'CND_TaxableAmt' => $cndTaxableAmt,
                'CND_Total' => 1 * $cndTotal,
                'usr_updated' => Auth::user()->id,
            ]);
            
            if ($request->applicant_type == 'individual_applicant') {
                ContractMasterAttachment::where('contractmast_id', $contractId)->update([
                    'comp_form_file' => null,
                    'comp_form_mime' => null,
                    'comp_form_size' => null,
                    'comp_icno_file' => null,
                    'comp_icno_mime' => null,
                    'comp_icno_size' => null,
                    'comp_bankstatement_file' => null,
                    'comp_bankstatement_mime' => null,
                    'comp_bankstatement_size' => null,
                ]);
                if ($request->file_individual_icno) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'icno_file' => $this->getFileBlob('individual_applicant', 'icno'),
                        'icno_mime' => Session::get('file_temp_individual_icno_mime'),
                        'icno_size' => Session::get('file_temp_individual_icno_size'),
                        ]);
                } 

                if ($request->file_individual_income) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'income_file' => $this->getFileBlob('individual_applicant', 'income'),
                        'income_mime' => Session::get('file_temp_individual_income_mime'),
                        'income_size' => Session::get('file_temp_individual_income_size'),
                    ]);
                }

                if ($request->file_individual_bankstatement) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'bankstatement_file' => $this->getFileBlob('individual_applicant', 'bankstatement'),
                        'bankstatement_mime' => Session::get('file_temp_individual_bankstatement_mime'),
                        'bankstatement_size' => Session::get('file_temp_individual_bankstatement_size'),
                    ]);
                }
            } else {
                ContractMasterAttachment::where('contractmast_id', $contractId)->update([
                    'icno_file' => null,
                    'icno_mime' => null,
                    'icno_size' => null,
                    'income_file' => null,
                    'income_mime' => null,
                    'income_size' => null,
                    'bankstatement_file' => null,
                    'bankstatement_mime' => null,
                    'bankstatement_size' => null,
                ]);

                if ($request->file_company_form) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'comp_form_file' => $this->getFileBlob('self_employed', 'form'),
                        'comp_form_mime' => Session::get('file_temp_company_form_mime'),
                        'comp_form_size' => Session::get('file_temp_company_form_size'),
                    ]);
                }

                if ($request->file_company_icno) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'comp_icno_file' => $this->getFileBlob('self_employed', 'icno'),
                        'comp_icno_mime' => Session::get('file_temp_company_icno_mime'),
                        'comp_icno_size' => Session::get('file_temp_company_icno_size'),
                    ]);
                }
                
                if ($request->file_company_bankstatement) {
                    ContractMasterAttachment::where('contractmast_id', $contractId)->update([ 
                        'comp_bankstatement_file' => $this->getFileBlob('self_employed', 'bankstatement'),
                        'comp_bankstatement_mime' => Session::get('file_temp_company_bankstatement_mime'),
                        'comp_bankstatement_size' => Session::get('file_temp_company_bankstatement_size'),
                    ]);
                }
            }

            // remove all the temp session, and temp attachment
            $this->removeTempAttachment($request);
            $this->flushAllFileTempSession($request);

            $cnsoLogSeqNumber = SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')->select(['param_val'])->first();
            $cnsoLogSeqNumberNew = $cnsoLogSeqNumber->param_val + 1;

            SystemParamDetail::where('sysparam_cd', 'CNSOLOGSEQ')
                ->update(['param_val' => $cnsoLogSeqNumberNew]);

            $contractMaster = ContractMaster::where('id', $contractId)->first();
            $contractMasterDtl = ContractMasterDtl::where('contractmast_id', $contractId)->first();

            $contractMasterLog = ContractMasterLog::create([
                'rcd_grp' => $cnsoLogSeqNumberNew,
                'action' => 'ADD',
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

            if ($sendEmail == 'yes') {
                $hashids = new Hashids(config('app.salt'), 5);
                $contractIdEncode = $hashids->encode($contractMaster->id);

                $urlLink = URL::signedRoute(
                    'contract.email.verify', ['id' => $contractIdEncode]
                );

                $data = [
                    'title' => 'Email verification for ITSU Kubikt',
                    'content' => 'Click link to verify email for contract application. ',
                    'link' => $urlLink,
                    'warning' => ''
                ];
                Mail::send('page.auth.email', $data, function($message) use ($request) {
                    $message->to($request['email_of_applicant'], $request['name_of_applicant'])->subject('Hy, ' . $request['name_of_applicant']);
                });

                Session::flash('showSuccessMessage', 'Successfully submitted application form, Email has been sent to verify contract email');
            } else {
                Session::flash('showSuccessMessage', 'Successfully submitted application form');
            }

            DB::commit();

            return redirect('/customer/contract');
            
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
