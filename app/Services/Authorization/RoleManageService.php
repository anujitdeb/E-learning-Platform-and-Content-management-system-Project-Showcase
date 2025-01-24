<?php

namespace App\Services\Authorization;

use Exception;
use App\Models\Authorization\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Authorization\PermissionGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleManageService
{
    const SWW = "Something was wrong!";

    public function getRoles(array $data): LengthAwarePaginator
    {
        try {
            return Role::with(['createdBy:id,name'])
                ->withCount('permissions')
                ->DataSearch($data)
                ->orderBy('id', 'DESC')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function storeRole(array $data, int $userId): Role
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = $userId;
            $role = Role::create($data);
            $role->load('createdBy:id,name,employee_id');
            $role->permissions()->sync($data['permissions']);
            $role->permissions_count = count(array_unique($data['permissions']));
            DB::commit();
            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    public function getRole($role): Role
    {
        $role->permission_id = $role->permissions()->pluck('permissions.id');
        return $role;
    }

    public function updateRole(array $data, $role, int $userId): Role
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = $userId;
            $role->update($data);
            if ($role->deletable) {
                $role->permissions()->sync($data['permissions']);
            } else {
                $role->permissions()->syncWithoutDetaching($data['permissions']);
            }
            $role->load('createdBy:id,name,employee_id');
            $role->permissions_count = count(array_unique($data['permissions']));
            DB::commit();
            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    public function deleteRole($role): string
    {
        if (!$role->deletable) {
            throw new Exception("Could not delete!", 403);
        }
        try {
            $role->delete();
            return "Successfully deleted.";
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function getPermissions(): Collection
    {
        try {
            return PermissionGroup::with('permissions:id,permission_group_id,name')->select('id', 'name')->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    function getActiveRoles(): Collection
    {
        try {
            $role = Role::where('is_active', true)->where('id', '!=', 1)->select('id', 'name')->get();
            return $role;
        } catch (Exception $e) {
            throw new Exception("Something was wrong!", 500);
        }
    }
}
