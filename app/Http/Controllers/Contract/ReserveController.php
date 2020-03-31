<?php
namespace App\Http\Controllers\Contract;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

use Artisan;
use Session;
use DB;

use App\Models\ContractInvoice;

class ReserveController extends Controller
{
    public function __construct()
    {
        $this->middleware('local-environment');
    }

    public function generateInvoice(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'secret_pass' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        
        if ($request->secret_pass !== config('app.secret_dev_pass')) {
            Session::flash('showErrorMessage', 'Invalid Secret Dev Pass');
            return redirect()->back();
        }

        $command = Artisan::call('invoice:generate');
        $commandOutput = Artisan::output();
        // dd($commandOutput);

        Session::flash('showCommandOutput', $commandOutput);
        return redirect()->back();
    }
}
