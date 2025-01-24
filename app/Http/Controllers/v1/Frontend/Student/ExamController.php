<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Student\ExamService;

class ExamController extends Controller
{
    protected ExamService $service;

    public function __construct(ExamService $service)
    {
        $this->service = $service;
    }

    public function getExams(Request $request): array
    {
        try {
            return $this->service->getExams($request->all());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
