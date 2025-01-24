<?php

namespace App\Services\Lead;

use App\Models\UserLead;
use Carbon\Carbon;
use Exception;

class UserLeadService
{
    const somethingWentWrong  = "Something went wrong";

    public function storeLead(array $data, int $userID): string
    {
        $exits = UserLead::where([
            'user_id' => $userID,
            'type' => $data['type'],
            'status' => $data['status']
        ])->whereDate('created_at', '>=', Carbon::now()->subDays(3))
            ->where(function ($q) use ($data) {
                if (!empty($data['course_id'])) {
                    $q->where('course_id', $data['course_id']);
                }
            })->exists();
        if ($exits) {
            throw new Exception('Your request already has been placed.', 403);
        }

        try {
            $data['user_id'] = $userID;
            UserLead::create($data);
            return 'Your request is placed.';
        } catch (Exception $e) {
            throw new Exception(self::somethingWentWrong, $e->getCode());
        }
    }
}
