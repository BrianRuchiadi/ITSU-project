<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{
        logout as performLogout;
        login as performLogin;
      }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() 
    {
        return view('page.auth.login');
    }

    public function Login(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'bail|required|min:2',
            'password' => 'bail|required|min:6',
        ]);

        // check if it is a correct user credential
        $validUser = Auth::once(['userid' => $validatedData['user_id'], 'password' => $validatedData['password']]);
        if (!$validUser) { return back()->withErrors('User credentials is invalid'); }

        $user = User::where('userid', $validatedData['user_id'])->first();
        if (!$user) { return back()->withErrors('User has been removed. Please contact admin'); }
        if (!$user->acc_customer_module && !$user->acc_contract_module) { 
            return back()->withErrors('You are not authorized to view this page'); 
        }

        switch ($user->branchind) {
            case 0: // staff (ALLOW)
            case 4: // customer (ALLOW) (Need to handle referrer link)
                Auth::loginUsingId($user->id, true);
                return redirect($this->redirectTo);

            case 1: // branch
            case 2: // admin
            case 3: // custom staff
                return back()->withErrors('You are not authorized to view this page');
        }
      }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect('/login');
    }
}
