<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Hashids\Hashids;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    public function register(Request $request)
    {
        // example of decoding referrer code
        $hashIds = new Hashids(config('app.salt'), 5);
        $decoded = $hashIds->decode($request->referrer_code);
        // more validation needed to validate the referrer code

        $validator = Validator::make($request->all(), [
            'email_address' => 'required|string|email|max:255|unique:users,email',
            'user_id' => 'required|string|max:255|unique:users,userid',
            'password' => 'required|string|min:6|confirmed|same:password_confirmation',
            'name' => 'required|string',
        ]);

        if ($validator->fails()){
            return redirect()->back()->with('message', $validator->messages()->first())
                    ->with('status','Failed to Save Register Data !')
                    ->with('type','error');
        }
        
        try {
            $create = User::create([
                'userid' => $request['user_id'],
                'email' => $request['email_address'],
                'password' => Hash::make($request['password']),
                'name' => $request['name'],
                'branchind' => 4,
                'acc_customer_module' => 1,
                'status' => 0,
                'remember_token' => Str::random(10),
            ]);
            
            if ($create) {
                $data = [
                    'title' => 'Email verification for ITSU Kubikt',
                    'content' => 'Click the link below to complete the registration',
                    'link' => env('APP_URL') . '/register/verify?token='. $create['remember_token'],
                ];
                Mail::send('page.auth.email', $data, function($message) use ($request) {
                    $message->to($request['email_address'], $request['name'])->subject('Hy, ' . $request['name']);
                });
                return redirect('/login');
            }
        }catch (\Exception $e){
            return redirect()->back()->with('message', $e->getMessage())
                    ->with('status','Failed to Save Register Data !')
                    ->with('type','error');
        }
    }

    public function showRegistrationForm()
    {
        return view('page.auth.register');
    }

    public function showVerifiedRegister(Request $request)
    {
        return view('page.auth.verified')->with('token', $request['token']);
    }

    public function verifyRegister(Request $request) 
    {
        User::where('remember_token', $request['token'])->update([
            'status' => 1,
            'remember_token' => null
        ]);
        
        return redirect('/login');
    }
}
