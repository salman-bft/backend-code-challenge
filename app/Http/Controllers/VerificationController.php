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
            $validatedData = $request->validated();
            $requestInstance = new Request($validatedData);
            $verification = $this->verificationService->verifyCode($requestInstance);

            return $this->sendSuccess('Verified'); // Successful verification
        } catch (Exception $e) {
            
            return $this->sendError($e->getMessage());
        }
    }
}
