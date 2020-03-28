<?php
namespace App\Http\Controllers\Contract;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function showInvoicesByGeneratedDate(Request $request) {
        return 1;
        // return view('page.contract.home');
    }
}
