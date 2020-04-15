<?php
namespace App\Http\Controllers\Customer;

use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Hashids\Hashids;
use Carbon\Carbon;
use Session;
use DB;

use App\Models\ContractDeliveryOrder;
use App\Models\IrsCity;
use App\Models\IrsState;
use App\Models\IrsCountry;

use App\Models\User;

class DeliveryController extends Controller
{
   public function getDeliveryOrder(Request $request, ContractDeliveryOrder $contractDelivery) {
        $companyAddress = DB::table('irs_company')
            ->join('irs_city', 'irs_company.CO_MainCity', '=', 'irs_city.CI_ID')
            ->join('irs_state', 'irs_company.CO_MainState', '=', 'irs_state.ST_ID')
            ->join('irs_country', 'irs_company.CO_MainCountry', '=', 'irs_country.CO_ID')
            ->where('irs_company.CO_Code', '=', env('BRANCH_ID'))
            ->select([
                'irs_company.id',
                'irs_company.CO_Name',
                'irs_company.CO_MainAddress1',
                'irs_company.CO_MainAddress2',
                'irs_company.CO_MainAddress3',
                'irs_company.CO_MainAddress4',
                'irs_company.CO_MainPostcode',
                'irs_company.CO_MainCity',
                'irs_company.CO_MainState',
                'irs_company.CO_MainCountry',
                'irs_company.CO_Phone1',
                'irs_company.CO_Email',
                'irs_company.CO_Website',
                'irs_city.CI_Description',
                'irs_state.ST_Description',
                'irs_country.CO_Description'
            ])->first();

        $deliveryOrder = DB::table('contractdeliveryorder')
            ->join('contractdeliveryorderdtl', 'contractdeliveryorder.id', '=' , 'contractdeliveryorderdtl.contractdeliveryorder_id')
            ->join('customermaster', 'contractdeliveryorder.CDOH_CustomerID', '=', 'customermaster.id')
            ->where('contractdeliveryorder.id', $contractDelivery->id)
            ->select([
                'contractdeliveryorder.id',
                'contractdeliveryorder.CDOH_DocNo',
                'contractdeliveryorder.CDOH_ContractDocNo',
                'contractdeliveryorder.CDOH_DocDate',
                'contractdeliveryorder.CDOH_Address1', 
                'contractdeliveryorder.CDOH_Address2',
                'contractdeliveryorder.CDOH_Postcode',
                'contractdeliveryorder.CDOH_City',
                'contractdeliveryorder.CDOH_State',
                'contractdeliveryorder.CDOH_Country',
                'contractdeliveryorder.CDOH_InstallAddress1',
                'contractdeliveryorder.CDOH_InstallAddress2',
                'contractdeliveryorder.CDOH_InstallPostcode',
                'contractdeliveryorder.CDOH_InstallCity',
                'contractdeliveryorder.CDOH_InstallState',
                'contractdeliveryorder.CDOH_InstallCountry',
                'contractdeliveryorderdtl.CDOD_Description',
                'contractdeliveryorderdtl.CDOD_Qty',
                'contractdeliveryorderdtl.CDOD_SerialNo',
                'customermaster.Cust_NAME'
            ])->first();

        $deliveryOrder->City_Description = IrsCity::where('CI_ID', $deliveryOrder->CDOH_City)->pluck('CI_Description')->first();
        $deliveryOrder->State_Description = IrsState::where('ST_ID', $deliveryOrder->CDOH_State)->pluck('ST_Description')->first();
        $deliveryOrder->Country_Description = IrsCountry::where('CO_ID', $deliveryOrder->CDOH_Country)->pluck('CO_Description')->first();

        $deliveryOrder->InstallCity_Description = IrsCity::where('CI_ID', $deliveryOrder->CDOH_InstallCity)->pluck('CI_Description')->first();
        $deliveryOrder->InstallState_Description = IrsState::where('ST_ID', $deliveryOrder->CDOH_InstallState)->pluck('ST_Description')->first();
        $deliveryOrder->InstallCountry_Description = IrsCountry::where('CO_ID', $deliveryOrder->CDOH_InstallCountry)->pluck('CO_Description')->first();

        $printDate = Carbon::now()->toDateString();
        return view('page.customer.delivery-order-detail', compact('companyAddress', 'deliveryOrder', 'printDate'));
   }
}
