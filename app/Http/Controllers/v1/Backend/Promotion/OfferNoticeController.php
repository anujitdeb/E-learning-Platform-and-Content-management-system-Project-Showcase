<?php

namespace App\Http\Controllers\v1\Backend\Promotion;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promotion\OfferNoticeRequest;
use App\Services\Promotion\OfferNoticeService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Backend\Promotion\OfferNoticeResource;
use App\Models\Promotion\OfferNotice;

class OfferNoticeController extends Controller
{
    protected OfferNoticeService $service;

    public function __construct(OfferNoticeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $notices = $this->service->getOfferNotice($request->all());
            return OfferNoticeResource::collection($notices);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(OfferNoticeRequest $request): JsonResponse
    {
        try {
            $notice = $this->service->storeOfferNotice($request->validated());
            return successResponse(new OfferNoticeResource($notice), 'Successfully stored');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(OfferNotice $notice): JsonResponse
    {
        try {
            return successResponse(new OfferNoticeResource($notice));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(OfferNoticeRequest $request, OfferNotice $notice): JsonResponse
    {
        try {
            $notice = $this->service->updateOfferNotice($notice, $request->validated());
            return successResponse(new OfferNoticeResource($notice), 'Successfully updated');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(OfferNotice $notice): JsonResponse
    {
        try {
            $this->service->destroyOfferNotice($notice);
            return successResponse($notice, 'Successfully deleted');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
