<?php

namespace App\Services\Student;

use Exception;

class StudentNoticeService
{
    const SWW = "Something went wrong";

    public function getNotices(array $data): array
    {
        try {
            return (array) citsmp('get', '/get/notices', $data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function isUnreadNotice(): array
    {
        try {
            return (array) citsmp('get', '/is/unread/notice');
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function readNotice(int $notice_id): array
    {
        try {
            return (array) citsmp('put', '/read/notice/' . $notice_id);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
