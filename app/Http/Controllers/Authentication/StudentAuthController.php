<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Authentication\StudentLoginRequest;
use App\Services\AuthService\StudentAuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class StudentAuthController extends Controller
{
    protected StudentAuthService $service;

    /**
     * Create a new class instance.
     */
    public function __construct(StudentAuthService $service)
    {
        $this->service = $service;
    }

    public function login(StudentLoginRequest $request): JsonResponse
    {
        try {
            $data = $this->service->login($request->validated());
            return successResponse($data);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function logout()
    {
        try {
            $message = $this->service->logout();
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function check()
    {
        try {
            $status = $this->service->check();
            return successResponse(['status' => $status]);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
