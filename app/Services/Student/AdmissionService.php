<?php

namespace App\Services\Student;

use Exception;

class AdmissionService
{
    const SWW = "Something went wrong";

    public function getBatches(): array
    {
        try {
            return (array) citsmp('get', '/get/admission/batches');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
