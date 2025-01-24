<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Authentication\StudentOtpRequest;
use App\Http\Requests\Student\Authentication\StudentOtpResendRequest;
use App\Http\Requests\Student\Authentication\StudentRegistrationRequest;
use App\Http\Requests\Student\Authentication\StudentSetPasswordRequest;
use App\Services\AuthService\StudentAuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class StudentRegistrationController extends Controller
{
    protected StudentAuthService $service;

    /**
     * Create a new class instance.
     */
    public function __construct(StudentAuthService $service)
    {
        $this->service = $service;
    }

    public function registration(StudentRegistrationRequest $request): JsonResponse
    {
        try {
            $message = $this->service->studentRegistration($request->validated());
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function verifyOTP(StudentOtpRequest $request): JsonResponse
    {
        try {
            $data = $this->service->verifyOtp($request->validated());
            return successResponse($data, "Verified successfully.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function setPassword(StudentSetPasswordRequest $request): JsonResponse
    {
        try {
            $message = $this->service->setPassword($request->validated(), auth('api')->user());
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function resendOTP(StudentOtpResendRequest $request): JsonResponse
    {
        try {
            $message = $this->service->sendOTP($request->email_or_number);
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
