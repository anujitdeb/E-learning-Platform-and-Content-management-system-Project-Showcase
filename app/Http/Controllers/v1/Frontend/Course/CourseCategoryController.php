<?php

namespace App\Http\Controllers\v1\Frontend\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Course\CourseCategoryResource;
use App\Services\Course\CourseCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    protected CourseCategoryService $service;

    public function __construct(CourseCategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->service->appCourseCategories(request()->header('lang-id', 1));
            return successResponse(CourseCategoryResource::collection($categories));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
