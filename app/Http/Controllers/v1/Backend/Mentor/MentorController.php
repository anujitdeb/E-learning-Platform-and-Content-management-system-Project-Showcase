<?php

namespace App\Http\Controllers\v1\Backend\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\MentorRequest;
use App\Http\Resources\Backend\Mentor\MentorResource;
use App\Models\Mentor\Mentor;
use App\Services\Mentor\MentorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MentorController extends Controller
{
    protected MentorService $service;

    public function __construct(MentorService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $mentors = $this->service->getMentors($request->all());
            return MentorResource::collection($mentors);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MentorRequest $request): JsonResponse
    {
        try {
            $mentor = $this->service->storeMentor($request->validated());
            return successResponse(new MentorResource($mentor), 'Successfully stored');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update  created resource in storage.
     */
    public function update(MentorRequest $request, Mentor $mentor): JsonResponse
    {
        try {
            $mentor = $this->service->updateMentor($request->validated(), $mentor);
            return successResponse(new MentorResource($mentor), 'Successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(Mentor $mentor): JsonResponse
    {
        try {
            $mentor = $this->service->showMentor($mentor);
            return successResponse(new MentorResource($mentor));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mentor $mentor): JsonResponse
    {
        try {
            $this->service->deleteMentor($mentor);
            return successResponse([], 'Deleted successfully.');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
