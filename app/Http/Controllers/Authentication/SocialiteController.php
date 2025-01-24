<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Services\AuthService\SocialiteService;

class SocialiteController extends Controller
{
    protected SocialiteService $service;

    public function __construct(SocialiteService $service)
    {
        $this->service = $service;
    }

    public function googleLogin(): RedirectResponse|JsonResponse
    {
        try {
            return Socialite::driver('google')->stateless()->redirect();
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function googleAuthenticationCallBack(): RedirectResponse|JsonResponse
    {
        try {
            return $this->service->googleAuthenticationCallBack();
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function googleAuthentication(): JsonResponse
    {
        try {
            $data = $this->service->googleAuthentication();

            return successResponse($data, 'User authenticated successfully.');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
