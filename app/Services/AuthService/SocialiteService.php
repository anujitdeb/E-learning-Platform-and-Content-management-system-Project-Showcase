<?php

namespace App\Services\AuthService;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService
{
    public function googleAuthenticationCallBack(): RedirectResponse
    {
        try {
            return redirect()->away(config('app.mobile_app_url') . '?' . http_build_query(request()->all()));
        } catch (Exception $e) {
            throw new Exception($e->getCode() == 401 ? $e->getMessage() : "Something went wrong!", $e->getCode());
        }
    }

    public function googleAuthentication(): array
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser || !$googleUser->getEmail()) {
                throw new Exception("Invalid Google user data.", 401);
            }

            $user = User::firstOrCreate(
                [
                    'email' => $googleUser->getEmail()
                ],
                [
                    'name' => $googleUser->getName(),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_number' => false,
                ]
            );

            if ($user->email_verified_at == null) {
                $user->update([
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]);
            }

            if ($user->is_active) {
                return $this->setCredential($user);
            } else {
                throw new Exception("Failed to authenticate.", 401);
            }
        } catch (Exception $e) {
            throw new Exception($e->getCode() == 401 ? $e->getMessage() : "Something went wrong!", $e->getCode());
        }
    }

    private function setCredential(object $user): array
    {
        $data = [];
        $data['name'] = $user->name;
        $data['email'] = $user->email ?? null;
        $data['number'] = $user->number ?? null;
        $data['is_complete'] = $user->is_complete ?? null;
        $data['is_student'] = $user->is_student ?? null;
        $data['is_admitted'] = $user->is_admitted ?? null;
        $data['image'] = $user->image ? asset($user->image) : null;
        $data['token'] = $user->createToken('GoogleAuth', ['user'])->accessToken;
        return $data;
    }
}
