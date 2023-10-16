<?php
namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class VerificationService
{
    public function verifyCode($request)
    {

        $curTime = date("Y-m-d H:i:s");

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('verification_code_expiry', '>=', $curTime)
            ->first();

        if (isset($user)) {
            $user->update([
                'verification_code' => null,
                'verification_code_expiry' => null,
                'email_verified_at' => now(),
            ]);
            return true;
        } else {
            throw new Exception('Verification code is either expired or wrong');
        }

    }
}