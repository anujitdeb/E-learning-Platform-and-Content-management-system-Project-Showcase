<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Student\ClassModuleService;

class ClassModuleController extends Controller
{
    protected ClassModuleService $service;

    public function __construct(ClassModuleService $service)
    {
        $this->service = $service;
    }

    public function upcomingClasses(): JsonResponse
    {
        try {
            return successResponse($this->service->upcomingClasses());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getModules(Request $request): array
    {
        try {
            return $this->service->getModules($request->all());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
