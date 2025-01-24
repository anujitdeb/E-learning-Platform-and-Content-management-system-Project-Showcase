<?php

namespace App\Http\Controllers\v1\Frontend\Seminar;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Seminar\SeminarBookResource;
use App\Http\Resources\Frontend\Seminar\SeminarResource;
use App\Models\Seminar\Seminar;
use App\Services\Seminar\SeminarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeminarController extends Controller
{
    protected SeminarService $service;

    public function __construct(SeminarService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): ResourceCollection|JsonResponse
    {
        try {
            $seminars = $this->service->getSeminars(request()->header('lang-id', 1), $request->all());
            return SeminarResource::collection($seminars);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(int $seminar): JsonResponse
    {
        try {
            $seminar = $this->service->showSeminar(request()->header('lang-id', 1), $seminar);
            return successResponse(new SeminarResource($seminar));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getBookedSeminars(): ResourceCollection|JsonResponse
    {
        try {
            $seminars = $this->service->getBookedSeminars(auth('api')->id());
            return SeminarResource::collection($seminars);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function bookSeminar(Seminar $seminar): JsonResponse
    {
        try {
            $message = $this->service->bookSeminar($seminar, auth('api')->id());
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
