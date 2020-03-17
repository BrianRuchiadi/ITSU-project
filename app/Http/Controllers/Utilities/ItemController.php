<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\IrsItemMaster;
use Auth;


class ItemController extends Controller
{
    public function getItems(Request $request) {
        return [
            'data' => IrsItemMaster::whereNull('deleted_at')->
                        where('IM_Type', 1)->
                        where('IM_NonSaleItem_YN', 0)->
                        where('IM_Discontinue_YN', 0)->
                        get(),
        ];
    }
}
