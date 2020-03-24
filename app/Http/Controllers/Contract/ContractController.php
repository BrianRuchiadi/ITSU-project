<?php
namespace App\Http\Controllers\Contract;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

use App\Models\IrsItemMaster;
use App\Models\CustomerMaster;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\ContractMasterAttachment;

use App\Models\IrsCity;
use App\Models\IrsState;
use App\Models\IrsCountry;
use App\Models\User;

class ContractController extends Controller
{
    public function showPendingContractList(Request $request) {
        
            $contracts = DB::table('contractmaster')
                           ->join('customermaster', 'contractmaster.CNH_CustomerID', '=', 'customermaster.id')
                           ->where('contractmaster.CNH_Status', '=', 'Pending')
                           ->where('contractmaster.deleted_at', '=', null)
                           ->paginate(30);

        $user = Auth::user();
        return view('page.contract.pending-contract-list', compact('contracts', 'user'));
    }

    public function showSearchResult(Request $request) {    
        $contracts = DB::table('customermaster')
                       ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                       ->where('customermaster.Cust_NAME', 'like', '%' . $request->customer . '%')
                       ->where('customermaster.Cust_NRIC', 'like', '%' . $request->ic_no . '%')
                       ->where('customermaster.Cust_Phone1', 'like', '%' . $request->tel_no . '%')
                       ->where('customermaster.Cust_Phone2', 'like', '%' . $request->tel_no . '%')
                       ->where('contractmaster.CNH_DocNo', 'like', '%' . $request->contract_no . '%')
                       ->paginate(30);

        $user = Auth::user();

        return view('page.contract.pending-contract-list', compact('contracts', 'user'));
    }
    
    public function showCustomerContractDetail($contractId) {
        $contractDetails = DB::table('customermaster')
                            ->join('contractmaster', 'customermaster.id', '=', 'contractmaster.CNH_CustomerID')
                            ->join('contractmasterdtl', 'contractmaster.id', '=', 'contractmasterdtl.contractmast_id')
                            ->where('contractmaster.id', '=', $contractId)
                            ->first();

        $itemMaster = IrsItemMaster::where('IM_ID', $contractDetails->CND_ItemID)
                        ->where('IM_TYPE', '=', '1')
                        ->where('IM_NonSaleItem_YN', '=', 0)
                        ->where('IM_Discontinue_YN', '=', 0)
                        ->where('deleted_at', '=', null)
                        ->first();

        $city = IrsCity::where('CI_ID', $contractDetails->Cust_MainCity)
                  ->where('deleted_at', '=', null)
                  ->first();

        $state = IrsState::where('ST_ID', $contractDetails->Cust_MainState)
                  ->where('deleted_at', '=', null)
                  ->first();

        $country = IrsCountry::where('CO_ID', $contractDetails->Cust_MainCountry)
                  ->where('deleted_at', '=', null)
                  ->first();

        $agent1 = User::where('id', $contractDetails->CNH_SalesAgent1)
                  ->where('branchind', '=', '0')
                  ->where('deleted_at', '=', null)
                  ->first();

        $agent2 = User::where('id', $contractDetails->CNH_SalesAgent2)
                  ->where('branchind', '=', '0')
                  ->where('deleted_at', '=', null)
                  ->first();

        $attachment = ContractMasterAttachment::where('contractmast_id', $contractId)->first();

        return view('page.contract.pending-contract-detail', compact('contractDetails', 'itemMaster', 'city', 'state', 'country', 'agent1', 'agent2', 'attachment'));
    }
}
