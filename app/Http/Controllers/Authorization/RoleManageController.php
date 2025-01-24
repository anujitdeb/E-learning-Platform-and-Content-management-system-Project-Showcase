<?php

namespace App\Http\Controllers\Authorization;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Authorization\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Requests\Authorization\RoleManageRequest;
use App\Http\Resources\Authorization\RoleManageResource;
use App\Http\Resources\Authorization\PermissionGroupResource;
use App\Services\Authorization\RoleManageService;

class RoleManageController extends Controller
{
    protected RoleManageService $service;

    public function __construct(RoleManageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection | JsonResponse
    {
        try {
            $roles = $this->service->getRoles($request->all());
            return RoleManageResource::collection($roles);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleManageRequest $request): JsonResponse
    {
        try {
            $role = $this->service->storeRole($request->validated(), auth('admin-api')->user()->id);
            return successResponse(new RoleManageResource($role), "Successfully stored.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        try {
            $role = $this->service->getRole($role);
            return successResponse(new RoleManageResource($role));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleManageRequest $request, Role $role): JsonResponse
    {
        try {
            $role = $this->service->updateRole($request->validated(), $role, auth('admin-api')->user()->id);
            return successResponse(new RoleManageResource($role), "Successfully updated.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->service->deleteRole($role);
            return successResponse(new RoleManageResource($role), "Successfully deleted.");
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get permission group wise permissions
     */
    public function groupWisePermissions(): JsonResponse
    {
        try {
            $permissions = $this->service->getPermissions();
            return successResponse(PermissionGroupResource::collection($permissions));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }

    function activeRoles(): ResourceCollection | JsonResponse
    {
        try {
            $roles = $this->service->getActiveRoles();
            return RoleManageResource::collection($roles);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
