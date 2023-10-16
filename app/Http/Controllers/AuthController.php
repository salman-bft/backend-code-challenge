<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterationRequest;
use App\Models\User;
use App\Services\RegisterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends AppBaseController
{
    /**
     * The RegisterService instance.
     *
     * @var \App\Services\RegisterService
     */
    public $registerService;

    /**
     * AuthController constructor.
     *
     * @param \App\Services\RegisterService $registerService
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * Register a User.
     *
     * @param \App\Http\Requests\Auth\UserRegisterationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterationRequest $request)
    {
        try {
            $user = $this->registerService->registerUser($request->validated());

            return $this->sendSuccessResponse($user, 'Registration successful. Verification email sent.');
        } catch (Exception $e) {

            return $this->sendError($e->getMessage());
        }
    }

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
