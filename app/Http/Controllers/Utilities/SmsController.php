<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

use Twilio\Rest\Client;

use Auth;

class SmsController extends Controller
{

    function __construct()
    {
        $this->twilioAccountSid = config('app.twilio_sid');
        $this->twilioAuthToken = config('app.twilio_auth_token');
        $this->twilioVerifySid = config('app.twilio_verify_sid');

        $this->twilio = new Client($this->twilioAccountSid, $this->twilioAuthToken);

    }

    public function sendSms(Request $request) {      
        $validator = Validator::make($request->all(), [ 
            'contact_one_of_applicant' => 'required|string|min:8|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $sendSMS = $this->twilio->verify->v2->services($this->twilioVerifySid)
            ->verifications
            ->create($request->contact_one_of_applicant, "sms");

        return [
            'status' => $sendSMS->status
        ];
    }

    public function verifySms(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'contact_one_sms_tag' => 'required|string|min:6|max:6',
            'contact_one_of_applicant' => 'required|string|min:8|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            , 400);
        }
        
        $verification = $this->twilio->verify->v2->services($this->twilioVerifySid)
            ->verificationChecks
            ->create($request->contact_one_sms_tag, array('to' => $request->contact_one_of_applicant));

        return [
            'status' => $verification->status
        ];
    }
}
