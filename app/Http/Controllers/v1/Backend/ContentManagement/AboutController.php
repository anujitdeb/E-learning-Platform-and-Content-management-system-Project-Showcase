<?php

namespace App\Http\Controllers\v1\Backend\ContentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentManagement\AboutRequest;
use App\Http\Resources\ContentManagement\AboutResource;
use App\Models\ContentManagement\About;
use App\Services\ContentManagement\AboutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AboutController extends Controller
{
    protected AboutService  $service;

    public function __construct(AboutService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $abouts = $this->service->getAbouts($request->all());
            return AboutResource::collection($abouts);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AboutRequest $request): JsonResponse
    {
        try {
            $about = $this->service->storeAbout($request->validated());
            return successResponse(new AboutResource($about), 'Successfully stored');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about): JsonResponse
    {
        try {
            $about = $this->service->showAbout($about);
            return successResponse(new AboutResource($about));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AboutRequest $request, About $about): JsonResponse
    {
        try {
            $about = $this->service->updateAbout($request->validated(), $about);
            return successResponse(new AboutResource($about), 'Successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about): JsonResponse
    {
        try {
            $this->service->destroyAbout($about);
            return successResponse(new AboutResource($about), "Successfully deleted.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
