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
            'name_of_applicant' => 'required|string|min:3|max:50',
            'ic_number' => 'required|string',
            'contact_one_of_applicant' => 'required|string|min:8|max:20',
            'contact_two_of_applicant' => 'string|min:8|max:20',
            'email_of_application' => 'required|email',
            'address_one' => 'required|string|min:10',
            'address_two' => 'string|min:10',
            'postcode' => 'required|string|min:4|max:10',
            'city' => 'required|exists:irs_city,CI_ID',
            'state' => 'required|exists:irs_state,ST_ID',
            'country' =>  'required|exists:irs_country,CO_ID',
            'name_of_reference' => 'string|min:3|max:50',
            'contact_of_reference' => 'string|min:8|max:20',
            'seller_one' => 'required|exists:users,id',
            'seller_two' => 'exists:users,id',
            'tandcitsu' => 'required|boolean',
            'tandcctos' => 'required|boolean'
        ]);

        if ($validator->fails()){
            // if any of above validation fail 
            return redirect()->back()->with('message', $validator->messages()->first())
                    ->with('status','Failed to Submit Contract Form!')
                    ->with('type','error');
        }
    }
}
