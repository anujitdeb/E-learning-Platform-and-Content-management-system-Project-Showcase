<?php

namespace App\Http\Controllers\v1\Backend\Seminar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seminar\SeminarRequest;
use App\Http\Resources\Backend\Seminar\SeminarResource;
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $seminars = $this->service->getAllSeminars($request->all());
            return SeminarResource::collection($seminars);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeminarRequest $request): JsonResponse
    {
        try {
            $seminar = $this->service->storeSeminar($request->validated());
            return successResponse(new SeminarResource($seminar), 'Seminar successfully created');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seminar $seminar): JsonResponse
    {
        try {
            $this->service->show($seminar);
            return successResponse(new SeminarResource($seminar));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeminarRequest $request, Seminar $seminar): JsonResponse
    {
        try {
            $this->service->updateSeminar($request->validated(), $seminar);
            return successResponse(new SeminarResource($seminar), 'Seminar successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seminar $seminar): JsonResponse
    {
        try {
            $this->service->deleteSeminar($seminar);
            return successResponse($seminar, 'Seminar deleted successfully');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
