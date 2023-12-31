<?php

namespace App\Traits;


use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait EmailCodeSendTrait
{
    /**
     * Send email verification code to the user.
     *
     * @param array $request The user registration request data.
     *
     * @return array The input data with added verification code information.
     */
    public function emailCodeSend($request)
    {
        $input = $request;
        $otp = rand(100000, 999999);
        $input['verification_code'] = $otp;
        // Adding 10 minutes to the current time
        $cur_time = date("Y-m-d H:i:s");
        $duration = '+10 minutes';
        $input['verification_code_expiry'] = date('Y-m-d H:i:s', strtotime($duration, strtotime($cur_time)));
        // Update verification code information in the user record
        User::whereEmail($input['email'])->update($input);
        $details = [
            'subject' => 'Please verify your email address',
            'body' => 'Your code is : ' . $otp,
        ];
        // Mailing OTP to the user
        Mail::to($input['email'])->send(new OtpVerificationMail($details));

        return $input;
    }
}
