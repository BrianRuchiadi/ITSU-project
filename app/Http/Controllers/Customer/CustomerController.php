<?php
namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Hashids\Hashids;
use Session;

use App\Models\IrsSalesBranch;
use App\Models\CustomerMaster;
use App\Models\CustomerUserMap;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\SystemParamDetail;


class CustomerController extends Controller
{
    public function getReferralLink(Request $request) {
        $hashids = new Hashids(config('app.salt'), 5);
        $hashedId = $hashids->encode(Auth::user()->id);
        
        return [
            'url' => config('app.url') . '/register?ref=' . $hashedId
        ];
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
            'seller_one' => 'required|exists:users,id',
            'seller_two' => 'exists:users,id|nullable',
            'tandcitsu' => 'required|in:1',
            'tandcctos' => 'required|in:1'
        ]);

        // if any of above validation fail 
        if ($validator->fails()){
            return redirect()->back()->with('message', $validator->messages()->first())
                    ->with('status','Failed to Submit Contract Form!')
                    ->with('type','error');
        }

        // if all passed, check for SMS tag
        $validatorSMSTag = Validator::make($request->all(), [
            'contact_one_sms_tag' => 'required|string|min:6|max:6',
            'contact_one_sms_verified' => "required|in:'valid"
        ]);

        // if only SMS tag fail, then return

        if ($validatorSMSTag->fails()) {
            Session::flash('displaySMSTag', 'Display SMS Tag');

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

            return redirect()->back();
        }

        // if both validation passed, then only do next step insert etc etc
        try {
            $irsSalesBranch = IrsSalesBranch::where('SB_Code', config('app.branch_id'))
                                    ->select(['SB_DefaultWarehouse', 'SB_DefaultBinLocation'])
                                    ->first();
            
            $customerMasterByNRIC = CustomerMaster::where('Cust_NRIC', $request->ic_number)->whereNull('deleted_at')->get();
            $customerMasterByEmail = CustomerMaster::where('Cust_Email', $request->email_of_applicant)->whereNull('deleted_at')->get();

            if (!$customerMasterByNRIC && !$customerMasterByEmail) {
                // if both not found then, create record
                $systemParamDetail = SystemParamDetail::where('sysparam_cd', 'CUSTIDSEQ')->select(['param_val'])->first();
                $newRuningNumber = $systemParamDetail->param_val + 1;

                SystemParamDetail::where('sysparam_cd', 'CUSTIDSEQ')
                    ->update(['param_val' => $newRuningNumber]);
            } else {
                if ($customerMasterByNRIC) {
                    // update by using NRIC
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
                }

            }
          

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
