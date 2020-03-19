<?php
namespace App\Http\Controllers\Utilities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use Twilio\Rest\Client;
// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

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
        $request->validate([
            'contact_one_of_applicant' => 'required|string|min:8|max:20'
        ]);

        
        $sendSMS = $this->twilio->verify->v2->services($this->twilioVerifySid)
            ->verifications
            ->create($request->contact_one_of_applicant, "sms");

        return [
            'data' => 1,
            'status' => $sendSMS->status
        ];
        // return [
        //     'status' => $sendSMS->status
        // ];
    }

    public function verifySms(Request $request) {
        $request->validate([
            'contact_one_sms_tag' => 'required|string|min:6|max:6',
            'contact_one_of_applicant' => 'required|string|min:8|max:20'
        ]);
        
        $verification = $this->twilio->verify->v2->services($this->twilioVerifySid)
            ->verificationChecks
            ->create($request->contact_one_sms_tag, array('to' => $request->contact_one_of_applicant));

        return [
            'data' => 1,
            'status' => $verification->status
        ];
    }
}
