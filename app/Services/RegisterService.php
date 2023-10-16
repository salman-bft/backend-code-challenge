<?php
namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Models\User;
use App\Traits\EmailCodeSendTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegisterService
 *
 * @package App\Services
 */
class RegisterService
{
    use EmailCodeSendTrait;
    /**
     * Register a new user.
     *
     * @param array $request The user registration request data.
     *
     * @return \App\Models\User The registered user instance.
     */
    public function registerUser($request)
    {
        $input = $request;
        $input['password'] = Hash::make($request['password']);
        $user = User::create($input);
        $result = $this->emailCodeSend($input);

        return $user;
    }

}
