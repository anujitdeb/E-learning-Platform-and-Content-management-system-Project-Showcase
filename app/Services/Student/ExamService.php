<?php

namespace App\Services\Student;

use Exception;

class ExamService
{
    const SWW = "Something went wrong";

    public function getExams(array $data): array
    {
        try {
            return (array) citsmp('get', '/get/exams', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
