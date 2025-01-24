<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\LanguageRequest;
use App\Http\Resources\Settings\LanguageResource;
use App\Models\Settings\Language;
use App\Services\Settings\LanguageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Exception;
use Illuminate\Http\Request;

class LanguageController extends Controller
{

    protected LanguageService $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $language = $this->service->getLanguages($request->all());
            return LanguageResource::collection($language);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LanguageRequest $request): JsonResponse
    {
        try {
            $language = $this->service->storeLanguage($request->validated());
            return successResponse(new LanguageResource($language), "Successfully stored.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Language $language): JsonResponse
    {
        try {
            return successResponse(new LanguageResource($language));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LanguageRequest $request, Language $language): JsonResponse
    {
        try {
            $language = $this->service->updateLanguage($request->validated(), $language);
            return successResponse(new LanguageResource($language), "Successfully updated.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language): JsonResponse
    {
        try {
            $this->service->destroyLanguage($language);
            return successResponse(new LanguageResource($language), "Successfully deleted.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function activeLanguages(): JsonResponse
    {
        try {
            $languages = $this->service->getActiveLanguages();
            return successResponse(LanguageResource::collection($languages));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
