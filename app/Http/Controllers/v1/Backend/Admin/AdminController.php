<?php

namespace App\Http\Controllers\v1\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Resources\Backend\Admin\AdminResource;
use App\Models\Admin;
use App\Services\Admin\AdminService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminService $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $admins = $this->service->getAdmins($request->all());
            return AdminResource::collection($admins);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request): JsonResponse
    {
        try {
            $admin = $this->service->storeAdmin($request->validated());
            return successResponse(new AdminResource($admin), 'Successfully created ');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin): JsonResponse
    {
        try {
            return successResponse(new AdminResource($admin));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $request, Admin $admin): JsonResponse
    {
        try {
            $admin = $this->service->updateAdmin($request->validated(), $admin);
            return successResponse(new AdminResource($admin), 'Successfully updated ');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin): JsonResponse
    {
        try {
            $this->service->destroyAdmin($admin);
            return successResponse(new AdminResource($admin), 'Successfully deleted');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
