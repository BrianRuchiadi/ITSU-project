<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\IrsCountry;
use App\Models\IrsState;
use App\Models\IrsCity;
use Auth;


class CountryController extends Controller
{
    public function getCountriesOptions(Request $request) {
        return [
            'data' => IrsCountry::whereNull('deleted_at')->get(),
        ];
    }

    public function getStatesOptions(Request $request) {
        return [
            'data' => IrsState::whereNull('deleted_at')->
                        where('ST_Country', $request->co_id)->
                        get()
        ];
    }

    public function getCitiesOptions(Request $request) {
        return [
            'data' => IrsCity::whereNull('deleted_at')->
                        where('CI_State', $request->st_id)->
                        get()
        ];
    }
}
