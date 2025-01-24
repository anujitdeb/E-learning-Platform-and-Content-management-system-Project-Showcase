<?php

namespace App\Http\Controllers\v1\Frontend\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Course\CourseCurriculumResource;
use App\Http\Resources\Frontend\Course\CourseListResource;
use App\Http\Resources\Frontend\Course\CourseSoftwaresResource;
use App\Http\Resources\Frontend\Course\CourseSuccessStoryResource;
use App\Http\Resources\Frontend\Course\DepartmentWiseCoursesResource;
use App\Http\Resources\Frontend\Course\CourseFacilitiesResource;
use App\Http\Resources\Frontend\Course\SingleCourseResource;
use App\Models\Course\Course;
use App\Models\Course\CourseCurriculum;
use App\Services\Course\CourseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseController extends Controller
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $courses = $this->service->getActiveCourseWithFilter($request->all(), request()->header('lang-id', 1));
            return CourseListResource::collection($courses);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(Course $course): JsonResponse
    {
        try {
            $this->service->getSingleCourse($course, request()->header('lang-id', 1));
            return successResponse(new SingleCourseResource($course));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getCurriculums(int $course): JsonResponse
    {
        try {
            $curriculums = $this->service->getCourseCurriculums($course, request()->header('lang-id', 1));
            return successResponse($curriculums->data ?? []);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getSuccessStories(int $course): ResourceCollection | JsonResponse
    {
        try {
            $successStories = $this->service->getSuccessStories($course);
            return CourseSuccessStoryResource::collection($successStories);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getFacilities(int $course, Request $request): ResourceCollection | JsonResponse
    {
        try {
            $facilities = $this->service->getFacilities($course, request()->header('lang-id', 1), $request->all());
            return CourseFacilitiesResource::collection($facilities);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getSoftwares(int $course): ResourceCollection | JsonResponse
    {
        try {
            $softwares = $this->service->getSoftwares($course, request()->header('lang-id', 1));
            return CourseSoftwaresResource::collection($softwares);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function departmentsWithCourses(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $courses = $this->service->getDepartmentsWithCourses(request()->header('lang-id', 1), $request->all());
            return DepartmentWiseCoursesResource::collection($courses);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
