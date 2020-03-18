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

    public function getStatesOptions(Request $request, IrsCountry $country) {
        return [
            'data' => IrsState::whereNull('deleted_at')->
                        where('ST_Country', $country->CO_ID)->
                        get()
        ];
    }

    public function getCitiesOptions(Request $request, IrsState $state) {
        return [
            'data' => IrsCity::whereNull('deleted_at')->
                        where('CI_State', $state->ST_ID)->
                        get()
        ];
    }
}
