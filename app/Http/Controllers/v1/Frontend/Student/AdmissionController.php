<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Student\AdmissionService;

class AdmissionController extends Controller
{
    protected AdmissionService $service;

    public function __construct(AdmissionService $service)
    {
        $this->service = $service;
    }

    public function getBatches(): JsonResponse
    {
        try {
            return successResponse($this->service->getBatches());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
