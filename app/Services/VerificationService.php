<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class VerificationService
{
    /**
     * Verify the provided code for email verification.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool True if the verification is successful, otherwise an exception is thrown.
     * @throws \Exception If the verification code is either expired or wrong.
     */
    public function verifyCode($request)
    {
        $curTime = now(); // Current time
        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('verification_code_expiry', '>=', $curTime)
            ->first();
        if (isset($user)) {
            // Mark the user as verified
            $user->update([
                'verification_code' => null,
                'verification_code_expiry' => null,
                'email_verified_at' => $curTime,
            ]);

            return true;
        } else {
            throw new Exception('Verification code is either expired or wrong');
        }
    }
}
