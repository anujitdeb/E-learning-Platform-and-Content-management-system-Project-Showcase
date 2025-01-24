<?php

namespace App\Services\Student;

use Exception;

class ClassModuleService
{
    const SWW = "Something went wrong";

    public function upcomingClasses(): array
    {
        try {
            return (array) citsmp('get', '/get/upcoming/classes');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getModules(array $data): array
    {
        try {
            return (array) citsmp('get', '/get/modules', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
