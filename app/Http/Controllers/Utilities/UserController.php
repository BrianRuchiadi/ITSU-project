<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Hashids\Hashids;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function getUsers(Request $request) {
        $decoded = null;
        if ($request->ref) {
            $hashIds = new Hashids(config('app.salt'), 5);
            $decoded = $hashIds->decode($request->ref)[0];
        }

        return [
            'decoded_referrer_id' => $decoded,
            'data' => User::whereNull('deleted_at')->
                        where('branchind', 0)->
                        get(),
        ];
    }
}
