<?php

namespace App\Http\Controllers\v1\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseContentRequest;
use App\Http\Resources\Backend\Course\CourseContentResource;
use App\Http\Resources\Backend\Course\CourseResource;
use App\Models\Course\Course;
use App\Services\Course\CourseContentService;
use Exception;
use Illuminate\Http\JsonResponse;

class CourseContentController extends Controller
{
    protected CourseContentService $service;

    public function __construct(CourseContentService $service)
    {
        $this->service = $service;
    }

    public function show(Course $course): JsonResponse
    {
        try {
            $course = $this->service->getCourseWiseCourseContent($course);
            return successResponse(new CourseResource($course));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(CourseContentRequest $request, Course $course): JsonResponse
    {
        try {
            $course = $this->service->updateCourseContent($request->validated(), $course);
            return successResponse(new CourseResource($course), "Successfully updated.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
