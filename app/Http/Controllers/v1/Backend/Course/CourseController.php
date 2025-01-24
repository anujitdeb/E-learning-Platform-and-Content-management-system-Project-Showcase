<?php

namespace App\Http\Controllers\v1\Backend\Course;

use App\Http\Requests\Course\CourseRequest;
use App\Http\Resources\Backend\Course\CourseResource;
use App\Models\Course\Course;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Services\Course\CourseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class CourseController extends Controller
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection|JsonResponse
    {
        try {
            $courses = $this->service->getCourses($request->all());
            return CourseResource::collection($courses);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request): JsonResponse
    {
        try {
            $course = $this->service->storeCourse($request->validated());
            return successResponse(new CourseResource($course), 'Successfully stored.');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): JsonResponse
    {
        try {
            $this->service->destroyCourse($course);
            return successResponse(new CourseResource($course), "Successfully deleted.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function activeCourses(): ResourceCollection|JsonResponse
    {
        try {
            $courses = $this->service->getActiveCourses();
            return CourseResource::collection($courses);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function statusUpdate(Course $course): string|JsonResponse
    {
        try {
            $response = $this->service->courseStatusUpdate($course);
            return successResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
