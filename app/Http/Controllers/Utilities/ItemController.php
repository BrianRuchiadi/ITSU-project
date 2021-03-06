<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\IrsItemMaster;
use App\Models\IrsItemRentalOpt;
use Auth;


class ItemController extends Controller
{
    public function getItems(Request $request) {
        return [
            'data' => IrsItemMaster::whereNull('deleted_at')->
                        where('IM_Type', 1)->
                        where('IM_NonSaleItem_YN', 0)->
                        where('IM_Discontinue_YN', 0)->
                        where('rental_ind', 1)->
                        get(),
        ];
    }

    public function getRentalMonthOptions(Request $request) {
        return [
            'data' => IrsItemRentalOpt::whereNull('deleted_at')->
                        where('IR_ItemID', $request->item_id)->
                        get(),
        ];
    }

    public function getRentalMonthOptionsPrice(Request $request) {
        return [
            'request' => $request->all(),
            'data' => IrsItemRentalOpt::whereNull('deleted_at')->
                        where('IR_ItemID', $request->item_id)->
                        where('IR_OptionKey', $request->option_key)->
                        first(),
        ];
    }
}
