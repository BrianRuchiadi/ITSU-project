<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Hashids\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    public function showResetPasswordForm() 
    { 
        return view('page.auth.reset-password');
    }

    public function sendEmailLink(Request $request) 
    {
        $user = User::where('userid', $request['user_id'])->first();

        if (!$user) {
            return back()->withErrors('User ID does not exist');
        }

        if ($user->password_reset == 1) {
            return back()->withErrors('Reset password request have been sent, please check email');
        }

        $hashids = new Hashids(config('app.salt'), 5);
        $hashedId = $hashids->encode($user->id);

        $urlLink = URL::temporarySignedRoute(
            'auth.reset.verify', now()->addMinutes(1), ['id' => $hashedId]
        );

        $data = [
            'title' => 'Email verification for ITSU Kubikt',
            'content' => 'Click the link below to reset password',
            'link' => $urlLink,
            'warning' => 'Link will expired in 1 hour'
        ];

        Mail::send('page.auth.email', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Hy, ' . $user->name);
        });

        $user->update(['password_reset' => 1]);
        Session::flash('flash_notification', [
            'level'     => 'warning',
            'message'   => 'Email have been sent'
        ]);

        return redirect('/login');
    }

    public function showVerifiedReset(Request $request)
    {
        return view('page.auth.reset-verified')->with('id', $request['id']);
    }

    public function verifyReset(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|same:new_password_confirmation',
        ]);

        if ($validator->fails()){
            return redirect()->back()->with('message', $validator->messages()->first())
                    ->with('status','Failed to Reset Password !')
                    ->with('type','error');
        }

        try {
            $hashids = new Hashids(config('app.salt'), 5);
            $hashedId = $hashids->decode($request->id);
            User::where('id', $hashedId)->update(
                [
                    'password' => Hash::make($request['new_password']),
                    'password_reset' => 0,
                    'password_changed_at' => Carbon::now()
                ]);

            Session::flash('flash_notification', [
                'level'     => 'warning',
                'message'   => 'Reset Password Successful'
            ]);

            return redirect('/login');

        }catch (\Exception $e){
            return redirect()->back()->with('message', $e->getMessage())
                    ->with('status','Failed to Save Register Data !')
                    ->with('type','error');
        }
    }
}
