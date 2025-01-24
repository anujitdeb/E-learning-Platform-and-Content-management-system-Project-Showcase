<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

function successResponse($data = null, $message = 'Operation successful.', int $httpCode = Response::HTTP_OK): JsonResponse
{
    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data
    ], $httpCode);
}

function errorResponse($message = 'Something was wrong!', int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
{
    return response()->json([
        'status' => false,
        'message' => $message,
        'data' => null
    ], $httpCode);
}
