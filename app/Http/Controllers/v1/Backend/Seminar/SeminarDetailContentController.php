<?php

namespace App\Http\Controllers\v1\Backend\Seminar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seminar\SeminarDetailContentRequest;
use App\Services\Seminar\SeminarDetailContentService;
use Exception;
use Illuminate\Http\JsonResponse;

class SeminarDetailContentController extends Controller
{
    protected SeminarDetailContentService $service;

    public function __construct(SeminarDetailContentService $service)
    {
        $this->service = $service;
    }

    public function show(int $seminar): JsonResponse
    {
        try {
            $detail_content = $this->service->getSeminarContent($seminar);
            return successResponse($detail_content);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(SeminarDetailContentRequest $request, int $seminar): JsonResponse
    {
        try {
            $message = $this->service->updateSeminarDetailContent($request->validated(), $seminar);
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
