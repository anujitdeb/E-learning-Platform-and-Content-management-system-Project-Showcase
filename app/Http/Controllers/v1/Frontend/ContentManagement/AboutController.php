<?php

namespace App\Http\Controllers\v1\Frontend\ContentManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ContentManagement\AboutResource;
use App\Services\ContentManagement\AboutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AboutController extends Controller
{
    protected AboutService $service;
    public function __construct(AboutService $service)
    {
        $this->service = $service;
    }
    public function index() : ResourceCollection | JsonResponse
    {
        try {
            $abouts = $this->service->getActiveAbouts(request()->header('lang-id', 1));
            return AboutResource::collection($abouts);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
