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
            'product' => 'required|exists:irs_itemmaster,id',
            'no_of_installment_month' => 'required|numeric',
            'name_of_applicant' => 'required|string',
            'ic_number' => 'required|string',
            ''
        ]);
    }
}
