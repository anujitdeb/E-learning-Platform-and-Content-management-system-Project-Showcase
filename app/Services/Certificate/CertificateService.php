<?php

namespace App\Services\Certificate;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CertificateService
{
    const SOMETHING_WENT_WRONG = "Something went wrong!";

    public function getCertificate(string $certificate_id)/*: array*/
    {
        try {
            $request_time = now();
            $secretKeyConfig = config('app.certificate.app_secret_key');
            if (!$secretKeyConfig) {
                throw new Exception("App secret key is not configured.", 500);
            }

            $hash = md5($secretKeyConfig . $certificate_id . $request_time);


            $data = [
                'certificate_id' => $certificate_id,
                'request_time' => $request_time,
                'hash' => $hash,
            ];

            $url = config('app.certificate.api_url') . "/api/cit-mobile-app/get-certificate";

            $data = $this->getData($url, $data);

            if (!empty($data['format']['background']['file'])) {
                $data['format']['background']['file'] = config('app.certificate.api_url') . '/' . $data['format']['background']['file'];
            }
            $data['url'] = 'https://certificate.citsmp.com/?certificate_id=' . $certificate_id;
            return $data;
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function getData(string $url, array $data): array
    {
        try {
            $response = Http::post($url, $data);

            if (!$response->successful()) {
                throw new Exception(self::SOMETHING_WENT_WRONG, $response->status());
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }
}
