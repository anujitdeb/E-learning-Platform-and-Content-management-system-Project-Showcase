<?php

namespace App\Services\AuthService;

use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class StudentAuthService
{
    public function studentRegistration(array $data): string
    {
        DB::beginTransaction();
        try {
            $userData = $this->setEmailOrNumber($data['email_or_number']);

            $user = User::firstOrCreate($userData, [
                'name' => $data['name'],
                'is_number' => !$this->isEmail($data['email_or_number'])
            ]);

            if ($user->number_verified_at || $user->email_verified_at) {
                throw new Exception("Already registered!", 403);
            }

            $message = $this->sendOTP($data['email_or_number'], $user);

            DB::commit();

            return $message;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getCode() == 403 ? $e->getMessage() : "Something was wrong!", $e->getCode());
        }
    }

    public function verifyOTP(array $data): array
    {
        $userData = $this->setEmailOrNumber($data['email_or_number']);

        $user = User::where($userData)->first();
        $otp = null;

        if ($user) {
            $otp = Otp::where('otp', $data['otp'])
                ->where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDay())
                ->first();
        }

        if (!$otp) {
            throw new Exception("Invalid data!", 403);
        }

        DB::beginTransaction();

        try {
            if ($user->email_verified_at || $user->number_verified_at) {
                $user->update([
                    "password" => null
                ]);
            } else {
                $user->update([
                    $user->email ? "email_verified_at" : "number_verified_at" => Carbon::now(),
                    "is_active" => true
                ]);
            }

            $otp->delete();

            $data = [];
            if (Auth::loginUsingId($user->id) && $user->is_active) {
                $user = auth()->user();
                $data = $this->setCredential($user);
            }

            DB::commit();

            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something was wrong!", $e->getCode());
        }
    }

    public function setPassword(array $data, object $user): string
    {
        if ($user && $user->password) {
            throw new Exception("Password exists!", 403);
        }

        try {
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
            return "Password set successfully.";
        } catch (Exception $e) {
            throw new Exception("Something was wrong!", $e->getCode());
        }
    }

    public function login(array $data): array
    {
        try {
            $userData = $this->setEmailOrNumber($data['email_or_number']);

            if (auth()->attempt([
                ...$userData,
                'password' => $data['password'],
                'is_active' => true
            ])) {
                $user = auth()->user();
                return [
                    'data' => $this->setCredential($user),
                    'message' => "Successfully logged in."
                ];
            }

            throw new Exception("Credential did not match.", 403);
        } catch (Exception $e) {
            throw new Exception($e->getCode() == 403 ? $e->getMessage() : "Something was wrong!", $e->getCode());
        }
    }

    public function setEmailOrNumber(string $emailOrNumber): array
    {
        $isEmail = $this->isEmail($emailOrNumber);
        $isEmail ? $userData['email'] = $emailOrNumber : $userData['number'] = $this->prepareNumber($emailOrNumber);
        return $userData;
    }

    private function isEmail(string $emailOrNumber): bool
    {
        return filter_var($emailOrNumber, FILTER_VALIDATE_EMAIL);
    }

    private function prepareNumber(string $number): string
    {
        $number = str_replace([' ', '-', '(', ')'], '', $number);
        return preg_replace('/^(?:\+?880|0)?/', '+880', $number);
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
        $data['token'] = $user->createToken('tokenName', ['user'])->accessToken;
        return $data;
    }

    public function sendOTP($emailOrNumber, ?object $user = null): string
    {
        try {
            if (!$user) {
                $userData = $this->setEmailOrNumber($emailOrNumber);
                $user = User::where($userData)->first();
            }

            if (!$user) {
                throw new Exception("Data not found!", 404);
            }

            Otp::where('user_id', $user->id)->delete();
            $otp = random_int(10000, 99999);
            Otp::create([
                'user_id' => $user->id,
                'otp' => $otp
            ]);

            $isEmail = $this->isEmail($emailOrNumber);
            return $this->sendOtpNotification($isEmail, $user, $otp);
        } catch (Exception $e) {
            throw new Exception($e->getCode() == 404 ? $e->getMessage() : "Something was wrong!", $e->getCode());
        }
    }

    public function sendOtpNotification(bool $isEmail, object $user, int $otp): string
    {
        if ($isEmail) {
            Notification::send($user, new SendOtpNotification($otp));
            return "Please check your email for the OTP.";
        } else {
            $message = "Your OTP is " . $otp . ". It will be valid for 3 minutes. Creative IT";
            sendSMS($user->number, $message);
            return "Please check your SMS for the OTP.";
        }
    }

    public function logout()
    {
        try {
            auth('api')->user()->token()->revoke();
            return "Successfully logged out.";
        } catch (Exception $e) {
            throw new Exception("Something was wrong!", $e->getCode());
        }
    }

    public function check()
    {
        try {
            $user = auth('api')->user();
            if ($user) {
                return $user->student_id ? 1 : 2;
            }
            return 3;
        } catch (Exception $e) {
            throw new Exception("Something was wrong!", $e->getCode());
        }
    }
}
