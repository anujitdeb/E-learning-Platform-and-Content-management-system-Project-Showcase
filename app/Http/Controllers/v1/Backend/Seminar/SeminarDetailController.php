<?php

namespace App\Http\Controllers\v1\Backend\Seminar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seminar\SeminarDetailRequest;
use App\Http\Resources\Backend\Seminar\ActiveSeminarDetailResource;
use App\Http\Resources\Backend\Seminar\SeminarDetailResource;
use App\Models\Seminar\SeminarDetail;
use App\Services\Seminar\SeminarDetailService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeminarDetailController extends Controller
{
    protected SeminarDetailService $service;

    public function __construct(SeminarDetailService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $seminar_details = $this->service->getSeminars($request->all());
            return SeminarDetailResource::collection($seminar_details);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeminarDetailRequest $request): JsonResponse
    {
        try {
            $detail = $this->service->storeSeminar($request->validated());
            return successResponse(new SeminarDetailResource($detail), 'Seminar detail has been added successfully.');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SeminarDetail $detail): JsonResponse
    {
        try {
            return successResponse(new SeminarDetailResource($detail));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeminarDetailRequest $request, SeminarDetail $detail): JsonResponse
    {
        try {
            $detail = $this->service->updateSeminer($request->validated(), $detail);
            return successResponse(new SeminarDetailResource($detail), 'Seminar detail has been updated.');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeminarDetail $detail): JsonResponse
    {
        try {
            $this->service->destroySeminar($detail);
            return successResponse([], 'Seminar has been deleted');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display a listing of active seminar details.
     */
    public function activeSeminarDetail(int $course_id = null): ResourceCollection | JsonResponse
    {
        try {
            $active_seminar_details = $this->service->getActiveSeminarDetail($course_id);
            return ActiveSeminarDetailResource::collection($active_seminar_details);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
