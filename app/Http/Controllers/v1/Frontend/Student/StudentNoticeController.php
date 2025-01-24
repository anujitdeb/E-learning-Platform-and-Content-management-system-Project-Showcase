<?php

namespace App\Http\Controllers\v1\Frontend\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentNoticeService;
use Exception;
use Illuminate\Http\Request;

class StudentNoticeController extends Controller
{
    protected StudentNoticeService $service;

    public function __construct(StudentNoticeService $service)
    {
        $this->service = $service;
    }

    public function getNotices(Request $request): array
    {
        try {
            return $this->service->getNotices($request->all());
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function isUnreadNotice(): array
    {
        try {
            return $this->service->isUnreadNotice();
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function readNotice(int $notice_id): array
    {
        try {
            return $this->service->readNotice($notice_id);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
