<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ChangePasswordController extends Controller
{
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    use AuthenticatesUsers{
        logout as performLogout;
      }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                function($attribute, $value, $fail) use ($user) {
                    if (Hash::check('current_password', $user->password)) {
                        return $fail($attribute.' is invalid.');
                    }
                }
            ],
            'new_password' => 'required|min:6|same:new_password_conf',
        ]);

        if ($validator->fails()){
            return redirect()->back()->with('message', $validator->messages()->first())
                    ->with('status','Failed to Save Register Data !')
                    ->with('type','error');
        }

        try{
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            $this->performLogout($request);
            
            Session::flash('flash_notification', [
                'level'     => 'warning',
                'message'   => 'Password have been successfully changed'
            ]);

            return redirect('/login');
        }catch(\Exception $e){
            return redirect()->back()
                    ->with('message', $e->getMessage()())
                    ->with('status','error')
                    ->with('type','error');
        }
    }
}
