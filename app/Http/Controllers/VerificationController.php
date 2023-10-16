<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CodeVerificationRequest;
use App\Services\VerificationService;
use Exception;
use Illuminate\Http\Request;

class VerificationController extends AppBaseController
{
    /**
     * The VerificationService instance.
     *
     * @var \App\Services\VerificationService
     */
    private $verificationService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\VerificationService $verificationService
     */
    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Verify the provided code.
     *
     * @param \App\Http\Requests\Auth\CodeVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function codeVerification(CodeVerificationRequest $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validated();
            // Create a new request instance with the validated data
            $requestInstance = new Request($validatedData);
            // Verify the code using the verification service
            $verification = $this->verificationService->verifyCode($requestInstance);
            // Respond with success message
            
            return $this->sendSuccess('Verified'); // Successful verification
        } catch (Exception $e) {
            // Respond with an error message in case of an exception
            return $this->sendError($e->getMessage());
        }
    }
}
