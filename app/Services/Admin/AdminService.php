<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;


class AdminService
{
    const SWW = 'Something went wrong!';

    public function getAdmins(array $data): LengthAwarePaginator
    {
        try {
            return Admin::with('role:id,name')->where('id', '!=', 1)
                ->DataFilter($data)
                ->select('id', 'role_id', 'employee_id', 'name', 'email', 'image', 'created_at', 'is_active')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function storeAdmin(array $data): Admin
    {
        try {
            if ($data['image']) {
                $image = $this->uploadImage($data['image']);
            }
            $password = Hash::make($data['password']);
            $data['image'] = $image ?? null;
            $data['password'] = $password;
            $admin = Admin::create($data);
            return $admin->load(['role:id,name']);
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function updateAdmin(array $data, Admin $admin): Admin
    {
        try {
            if (isset($data['image'])) {
                deleteImage($admin->image);
                $image = $this->uploadImage($data['image']);
            } else {
                $image = $admin->image;
            }
            $data['image'] = $image;
            $password = isset($data['password']) ? Hash::make($data['password']) : $admin->password;
            $data['password'] = $password;
            $admin->update($data);
            return $admin->load(['role:id,name']);
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function destroyAdmin(Admin $admin): void
    {
        try {
            deleteImage($admin->image);
            $admin->delete();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    private function uploadImage($image): string
    {
        $slug = rand(000000, 999999);
        return uploadBase64Image(
            $image,
            'images/admin/',
            'image_' . $slug,
            300,
            300,
        );
    }
}
