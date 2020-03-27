<?php
namespace App\Http\Controllers\Contract;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

use App\Models\User;
use App\Models\SystemParamDetail;
use Session;

class DeliveryController extends Controller
{

    public function showDeliveryOrder(Request $request) {
        return view('page.contract.delivery-order-list');
    }

    public function showCreateDeliveryOrder(Request $request) {
        return view('page.contract.delivery-order-create');
    }

    public function createDeliveryOrder(Request $request) {
        // validation
        $params = $request->all();

        DB::beginTransaction();
        
        try {
            // get DCOH_DocNo value
             
        } catch (Exception $e) {

        }

        return 1;
    }
}
