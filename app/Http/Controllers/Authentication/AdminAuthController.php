<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AdminAuthRequest;
use App\Services\AuthService\AdminAuthService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AdminAuthController extends Controller
{
    protected AdminAuthService $service;

    public function __construct(AdminAuthService $service)
    {
        $this->service = $service;
    }

    public function loginWithSSO(): JsonResponse
    {
        try {
            $data = $this->service->loginWithSSO();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function loginProcess(): RedirectResponse
    {
        try {
            return $this->service->loginProcess();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function login(AdminAuthRequest $request): JsonResponse
    {
        try {
            $data = $this->service->loginWithEmail($request->validated());
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $message = $this->service->logout();
            return response()->json([
                'message' => $message
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function check(): JsonResponse
    {
        try {
            $status = $this->service->check();
            return response()->json([
                'status' => $status
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function permissions(): JsonResponse
    {
        try {
            $permissions = $this->service->permissions();
            return response()->json([
                'permissions' => $permissions
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
