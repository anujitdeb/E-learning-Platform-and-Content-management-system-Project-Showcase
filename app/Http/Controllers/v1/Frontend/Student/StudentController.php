<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentImageRequest;
use App\Http\Requests\Student\StudentProfileUpdateRequest;
use App\Services\Student\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected StudentService $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function studentClassReport($admission_id = null): JsonResponse
    {
        try {
            return successResponse($this->service->studentClassReport($admission_id));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getFacebookGroups(): JsonResponse
    {
        try {
            return successResponse($this->service->getFacebookGroups());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getStudent(): JsonResponse
    {
        try {
            return successResponse($this->service->getStudent());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function profile(): JsonResponse
    {
        try {
            return successResponse($this->service->getProfile());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(StudentProfileUpdateRequest $request): JsonResponse
    {
        try {
            return successResponse($this->service->updateProfile($request->all()));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function uploadImage(StudentImageRequest $request): JsonResponse
    {
        try {
            return successResponse($this->service->uploadImage($request->all()));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getEduDegree(): JsonResponse
    {
        try {
            return successResponse($this->service->getEduDegree());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getEduBoard(): JsonResponse
    {
        try {
            return successResponse($this->service->getEduBoard());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getDivision(): JsonResponse
    {
        try {
            return successResponse($this->service->getDivision());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getDistrict(int $division_id = null): JsonResponse
    {
        try {
            return successResponse($this->service->getDistrict($division_id));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getUpazila(int $district_id = null): JsonResponse
    {
        try {
            return successResponse($this->service->getUpazila($district_id));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
