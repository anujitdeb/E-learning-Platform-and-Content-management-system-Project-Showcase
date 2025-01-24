<?php

namespace App\Http\Controllers\v1\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseCategoryRequest;
use App\Http\Requests\Course\HigherKeyRequest;
use App\Http\Resources\Backend\Course\CourseCategoryContentResource;
use App\Http\Resources\Backend\Course\CourseCategoryResource;
use App\Models\Course\CourseCategory;
use App\Services\Course\CourseCategoryService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class CourseCategoryController extends Controller
{

    protected CourseCategoryService  $service;

    public function __construct(CourseCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $categories = $this->service->getCourseCategories($request->all());
            return CourseCategoryResource::collection($categories);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->service->storeCourseCategory($request->validated());
            return successResponse(new CourseCategoryResource($category), 'Successfully stored');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseCategory $category): JsonResponse
    {
        try {
            $category = $this->service->showCourseCategory($category);
            return successResponse(new CourseCategoryResource($category));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseCategoryRequest $request, CourseCategory $category): JsonResponse
    {
        try {
            $category = $this->service->updateCourseCategory($request->validated(), $category);
            return successResponse(new CourseCategoryResource($category), 'Successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseCategory $category): JsonResponse
    {
        try {
            $this->service->destroyCourseCategory($category);
            return successResponse(new CourseCategoryResource($category), "Successfully deleted.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateHigherKey(HigherKeyRequest $request): JsonResponse
    {
        try {
            $this->service->updateHigherKey($request->validated());
            return successResponse(null, "Successfully updated.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function activeCourseCategories(): ResourceCollection
    {
        try {
            $activeCourseCategories = $this->service->activeCourseCategories();
            return CourseCategoryResource::collection($activeCourseCategories);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
