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
            'data' => IrsCountry::all(),
        ];
    }

    public function getStatesOptions(Request $request, IrsCountry $country) {
        return [
            'data' => IrsState::where('ST_Country', $country->CO_ID)->get()
        ];
    }

    public function getCitiesOptions(Request $request, IrsState $state) {
        return [
            'data' => IrsCity::where('CI_State', $state->ST_ID)->get()
        ];
    }
}
