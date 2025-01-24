<?php

namespace App\Services\AuthService;

use App\Models\Admin;
use App\Models\Authorization\Permission;
use Exception;
use Illuminate\Http\RedirectResponse;
use SSO;
use Illuminate\Support\Collection;

class AdminAuthService
{
    const ERROR_CREDENTIAL_DID_NOT_MATCH = "Credentials did not match!";
    const ERROR_SOMETHING_WAS_WRONG = "Something was wrong!";


    public function loginProcess(): RedirectResponse
    {
        try {
            return SSO::process()->redirect();
        } catch (Exception $e) {
            throw new Exception("Something was wrong!!!", 500);
        }
    }

    function loginWithSSO(): array
    {
        $data = SSO::user();
        if ($data) {
            $user = Admin::where(['employee_id' => $data['employee_id'], 'is_active' => true])->first();
            if ($user && auth('admin')->loginUsingId($user->id)) {
                return [
                    'data' => $this->setCredential($user),
                    'message' => "Successfully login"
                ];
            }
            throw new Exception("Unauthorized", 403);
        }
        throw new Exception(self::ERROR_CREDENTIAL_DID_NOT_MATCH, 404);
    }

    public function loginWithEmail(array $data): array
    {
        if (auth('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'is_active' => true])) {
            $user = auth('admin')->user();
            return [
                'data' => $this->setCredential($user),
                'message' => "Successfully logged in"
            ];
        }
        throw new Exception(self::ERROR_CREDENTIAL_DID_NOT_MATCH, 404);
    }

    private function setCredential(object $user): array
    {
        $data = [];
        $data['role'] = $user->role->name;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['employee_id'] = $user->employee_id;
        $data['image'] = asset($user->image ?? "default.jpg");
        $data['token'] = $user->createToken('tokenName',['admin'])->accessToken;
        return $data;
    }

    public function check(): bool
    {
        try {
            if (auth('admin-api')->check()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function logout(): string
    {
        try {
            auth('admin-api')->user()->token()->revoke();
            return "Successfully logged out.";
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    function permissions(): Collection
    {
        try {
            return Permission::whereHas('roles', function ($q) {
                $q->where('permission_role.role_id', auth('admin-api')->user()->role_id);
            })->select('slug')->get();
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }
}
