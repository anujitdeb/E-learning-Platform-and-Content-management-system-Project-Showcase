<?php

namespace App\Services\Student;

use Exception;

class StudentService
{
    const SWW = "Something went wrong";

    public function studentClassReport($admission_id = null): array
    {
        try {
            return (array) citsmp('get', '/class/report/' . $admission_id);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getFacebookGroups(): array
    {
        try {
            return (array) citsmp('get', '/get/facebook/groups');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getStudent(): array
    {
        try {
            return (array) citsmp('get', '/student');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getProfile()
    {
        try {
            return (array) citsmp('get', '/profile');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function updateProfile(array $data): array
    {
        try {
            return (array) citsmp('put', '/profile/update', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function uploadImage(array $data): array
    {
        try {
            return (array) citsmp('put', '/profile/image/upload', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getEduDegree(): array
    {
        try {
            return (array) citsmp('get', '/get/education/degree');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getEduBoard(): array
    {
        try {
            return (array) citsmp('get', '/get/education/board');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getDivision(): array
    {
        try {
            return (array) citsmp('get', '/get/division');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getDistrict(int $division_id = null): array
    {
        try {
            return (array) citsmp('get', '/get/district/' . $division_id);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getUpazila(int $district_id = null): array
    {
        try {
            return (array) citsmp('get', '/get/upazila/' . $district_id);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
