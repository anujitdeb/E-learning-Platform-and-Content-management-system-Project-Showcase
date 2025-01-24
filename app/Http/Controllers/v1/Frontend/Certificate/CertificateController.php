<?php

namespace App\Http\Controllers\v1\Frontend\Certificate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certificate\CertificateRequest;
use App\Services\Certificate\CertificateService;
use Exception;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    protected CertificateService $service;

    public function __construct(CertificateService $service)
    {
        $this->service = $service;
    }

    public function getCertificate(CertificateRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getCertificate($request->certificate_id);
            return successResponse($data);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
