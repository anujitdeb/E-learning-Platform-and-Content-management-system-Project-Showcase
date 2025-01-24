<?php

namespace App\Services\Student;

use Exception;

class AttendanceService
{
    const SWW = "Something went wrong";

    public function getAttendance(array $data): array
    {
        try {
            return (array) citsmp('get', '/get/attendance', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
