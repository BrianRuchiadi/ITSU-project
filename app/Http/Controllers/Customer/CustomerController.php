<?php
namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Hashids\Hashids;


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
            'name_of_applicant' => 'required|string',
            'ic_number' => 'required|string',
            'contact_one_of_applicant' => 'required|string',
            'contact_two_of_applicant' => 'string',
            'email_of_application' => 'required|email',
            'address_one' => 'required|string|min:10',
            'address_two' => 'string',
            'postcode' => 'required',
            'city' => 'required|exists:irs_city,CI_ID',
            'state' => 'required|exists:irs_state,ST_ID',
            'country' =>  'required|exists:irs_country,CO_ID',
            'name_of_reference' => 'string',
            'contact_of_reference' => 'string'
        ]);
    }
}
