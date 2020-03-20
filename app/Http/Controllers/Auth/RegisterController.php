<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use App\Models\User;
use App\Models\CustomerRegister;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

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
        // $hashIds = new Hashids(config('app.salt'), 5);
        // $decoded = $hashIds->decode($request->referrer_code);
        // more validation needed to validate the referrer code

        $validator = Validator::make($request->all(), [
            'email_address' => 'required|string|email|max:255|unique:users,email',
            'user_id' => 'required|string|max:255|unique:users,userid',
            'password' => 'required|string|min:6|confirmed|same:password_confirmation',
            'name' => 'required|string',
            'telephoneno' => 'required',
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }
        
        try {
            $create = CustomerRegister::create([
                'userid' => $request['user_id'],
                'email' => $request['email_address'],
                'password' => Hash::make($request['password']),
                'name' => $request['name'],
                'telephoneno' => $request['telephoneno'],
                'status' => 0,
            ]);
            
            if ($create) {
                $hashids = new Hashids(config('app.salt'), 5);
                $hashedId = $hashids->encode($create->id);

                $urlLink = URL::temporarySignedRoute(
                    'auth.register.verify', now()->addDays(1), ['id' => $hashedId]
                );

                $data = [
                    'title' => 'Email verification for ITSU Kubikt',
                    'content' => 'Click link to complete registration. ',
                    'link' => $urlLink,
                    'warning' => 'Link will expired in 1 day'
                ];
                Mail::send('page.auth.email', $data, function($message) use ($request) {
                    $message->to($request['email_address'], $request['name'])->subject('Hy, ' . $request['name']);
                });
                
                Session::flash('flash_notification', [
                    'level'     => 'warning',
                    'message'   => 'Please verify your email in order to login'
                ]);

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
        return view('page.auth.register-verified')->with('id', $request['id']);
    }

    public function verifyRegister(Request $request) 
    {
        $hashids = new Hashids(config('app.salt'), 5);
        $id = $hashids->decode($request['id']);
        $customerRegister = CustomerRegister::where('id', $id)->first();
        $userExist = User::where('userid', $customerRegister->userid)->exists();

        if ($userExist){
            return redirect()->back()->with('message', 'User Id Existed');
        }

        try {
            $create = User::create([
                'userid' => $customerRegister->userid,
                'email' => $customerRegister->email,
                'password' => $customerRegister->password,
                'name' => $customerRegister->name,
                'telephone' => $customerRegister->telephoneno,
                'branchind' => 4,
                'status' => 1,
                'acc_customer_module' => 1
            ]);

            $customerRegister->update(['status' => 1]);
            
            if ($create) {
                Session::flash('flash_notification', [
                    'level'     => 'warning',
                    'message'   => 'User successfully verified'
                ]);

                return redirect('/login');
            }
        }catch (\Exception $e){
            return redirect()->back()->with('message', $e->getMessage())
                    ->with('status','Failed to Save Register Data !')
                    ->with('type','error');
        }
        return redirect('/login');
    }
}
