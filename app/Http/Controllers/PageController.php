<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\CustomerUserMap;
use App\Models\CustomerMaster;

class PageController extends Controller
{
    public function index()
    {
        return view('page.home');
    }

    public function showReferralLink()
    {
        return view('page.customer.referral-link');
    }

    public function showApplicationForm(Request $request) 
    { 
        if (Auth::user()->branchind == 4) {
            $userMap = CustomerUserMap::where('users_id', Auth::user()->id)->get()->last();
            $customerMaster = CustomerMaster::where('id', ($userMap->customer_id) ?? null)->first();
            
        } else {
            $customerMaster = [];
        }
        
        return view('page.customer.application-form', compact('customerMaster'));
    }

    public function showChangePasswordForm() 
    { 
        return view('page.auth.change-password');
    }

    public function showContractEmailVerifying(Request $request) 
    {
        return view('page.utilities.email-verify')->with('id', $request['id']);
    }
}
