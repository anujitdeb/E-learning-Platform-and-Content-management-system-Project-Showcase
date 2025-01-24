<?php

namespace App\Http\Controllers\v1\Frontend\Promotion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\DeviceTokenRequest;
use App\Http\Resources\Backend\Promotion\OfferNoticeResource;
use App\Services\Promotion\OfferNoticeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OfferNoticeController extends Controller
{
    protected OfferNoticeService $service;

    public function __construct(OfferNoticeService $service)
    {
        $this->service = $service;
    }

    public function storeDeviceToken(DeviceTokenRequest $request): JsonResponse
    {
        try {
            $this->service->storeDeviceToken($request->validated());
            return successResponse([], 'Device token successfully stored');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getOfferNotices(): ResourceCollection|JsonResponse
    {
        try {
            $notices = $this->service->getOfferNoticesForUser();
            return OfferNoticeResource::collection($notices);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
