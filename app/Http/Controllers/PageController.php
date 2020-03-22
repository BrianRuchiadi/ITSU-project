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
        $this->flushAllFileTempSession($request);
        return view('page.customer.application-form');
    }

    public function showChangePasswordForm() 
    { 
        return view('page.auth.change-password');
    }

    // utilities function.
    public function flushAllFileTempSession(Request $request) {
        foreach ($request->session()->all() as $key => $val) {
            if (strpos($key, 'file_temp') !== false) {
                // remove the related file
                $request->session()->forget($key);
            }
        }

    }
}
