<?php

namespace App\Http\Controllers\v1\Backend\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\ContactRequest;
use App\Http\Resources\ContentManagement\ContactResource;
use App\Services\Apps\ContactService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactController extends Controller
{
    protected ContactService  $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection | JsonResponse
    {
        try {
            $contact = $this->service->getContact();
            return successResponse(new ContactResource($contact));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request): JsonResponse
    {
        try {
            $message = $this->service->updateContact($request->validated());
            return successResponse([], $message);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
