<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

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
        return view('page.customer.application-form');
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
