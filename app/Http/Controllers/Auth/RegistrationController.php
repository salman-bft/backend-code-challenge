<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterationRequest;
use App\Services\RegisterService;
use Exception;

class RegistrationController extends Controller
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
}
