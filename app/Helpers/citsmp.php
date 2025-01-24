<?php

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

/**
 * Make an HTTP request using the specified method.
 *
 * @param string $method The HTTP method to use (get, post, put, patch, delete).
 * @param string $url The URL to send the request to.
 * @param array $data The data to send with the request.
 * @return \Illuminate\Http\Client\Response The response from the HTTP request.
 * @throws \InvalidArgumentException If an invalid HTTP method is provided.
 */
function citsmp(string $method, string $url, array $data = [])
{
   // Ensure $method is lowercase
   $method = strtolower($method);
   try {
      $headers = setHeader();
   } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode());
   }
   // Check if the method exists in the Http facade
   if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']) && count($headers) == 3) {
      $fullUrl = config('citsmp.url') . $url;
      return Http::withHeaders($headers)->$method($fullUrl, $data)->json();
   } else {
      throw new InvalidArgumentException("Invalid HTTP method: $method");
   }
}


function setHeader(): array
{
   $data = [];
   $user = auth('api')->user();
   if ($user) {
      $data['Student-id'] = $user->student_id;
   } else {
      throw new Exception('User not authenticated', 401);
   }
   $data['Request-time'] = Carbon::now()->toString();
   $data['Authorization'] = md5(config('citsmp.key') . $data['Student-id'] . $data['Request-time'] . config('citsmp.secret'));
   return $data;
}
