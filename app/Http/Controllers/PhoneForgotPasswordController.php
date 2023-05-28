<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Twilio\Rest\Client;

class PhoneForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $user = DB::table('users')->where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = Password::getRepository()->create($user);

        $this->sendSms($request->phone, $token);

        return response()->json(['message' => 'Password reset SMS sent']);
    }

    private function sendSms($phoneNumber, $token)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

        $twilio = new Client($twilioSid, $twilioToken);

        $message = $twilio->messages->create(
            $phoneNumber,
            [
                'from' => $twilioPhoneNumber,
                'body' => 'Your password reset token: ' . $token
            ]
        );
    }
}
