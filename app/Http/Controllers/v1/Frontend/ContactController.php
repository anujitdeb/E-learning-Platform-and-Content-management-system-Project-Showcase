<?php

namespace App\Http\Controllers\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Apps\ContactResource;
use App\Services\Apps\ContactService;
use Exception;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    protected ContactService  $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }
    public function index(): JsonResponse
    {
        try {
            $content = $this->service->getActiveContact(request()->header('lang-id', 1));
            return successResponse(new ContactResource($content));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
