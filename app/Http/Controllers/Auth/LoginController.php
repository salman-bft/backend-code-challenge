<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     /**
     * Login a User and generate a new access token.
     *
     * @param \App\Http\Requests\Auth\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->email_verified_at !== null) {
                $token = $this->createNewToken($request->only('email', 'password'));

                return response()->json(['token' => $token]);
            }

            return $this->sendError('User not verified.');
        }

        return $this->sendError('Invalid credentials.');
    }

    /**
     * Create a new JWT token for the authenticated user.
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($credentials)
    {
        $token = auth()->attempt($credentials);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
