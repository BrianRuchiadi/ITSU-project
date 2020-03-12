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
}
