<?php

namespace App\Http\Controllers\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Apps\ProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ProfileService  $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $user = $this->service->getUser();
            return successResponse(new UserResource($user));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            $user = $this->service->updateProfile($request->validated());
            return successResponse(new UserResource($user), 'Successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
