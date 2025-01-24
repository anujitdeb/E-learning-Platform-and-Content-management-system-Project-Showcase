<?php

namespace App\Http\Controllers\v1\Frontend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\LanguageResource;
use App\Services\Settings\LanguageService;
use Exception;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    protected LanguageService $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $languages = $this->service->getActiveLanguages();
            return successResponse(LanguageResource::collection($languages));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
