<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Student\AttendanceService;

class AttendanceController extends Controller
{
    protected AttendanceService $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    public function getAttendance(Request $request): array
    {
        try {
            return $this->service->getAttendance($request->all());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
