<?php

namespace App\Http\Controllers\v1\Frontend\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Mentor\MentorExperiencesResource;
use App\Http\Resources\Frontend\Mentor\MentorMarketplacesResource;
use App\Http\Resources\Frontend\Mentor\MentorResource;
use App\Models\Mentor\Mentor;
use App\Services\Mentor\MentorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MentorController extends Controller
{
    protected MentorService $service;
    public function __construct(MentorService $service)
    {
        $this->service = $service;
    }

    public function index(int $department): ResourceCollection | JsonResponse
    {
        try {
            $mentors = $this->service->getActiveMentors($department, request()->header('lang-id', 1));
            return MentorResource::collection($mentors);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(Mentor $mentor): JsonResponse
    {
        try {
            $this->service->getMentor($mentor, request()->header('lang-id', 1));
            return successResponse(new MentorResource($mentor));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getExperiences(int $mentor): ResourceCollection | JsonResponse
    {
        try {
            $experiences = $this->service->getMentorExperiences($mentor);
            return MentorExperiencesResource::collection($experiences);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getMarketplaces(int $mentor): ResourceCollection | JsonResponse
    {
        try {
            $marketplaces = $this->service->getMentorMarketplaces($mentor);
            return MentorMarketplacesResource::collection($marketplaces);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
