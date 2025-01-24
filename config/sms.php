<?php


return [
   'url' => env('SMS_URL'),
   'url_bulk' => env('SMS_BULK_URL'),
   'sms' => [
      'apiKey' => env('SMS_API_KEY'),
      'clientId' => env('SMS_CLIENT_ID'),
      'senderId' => env('SMS_SENDER_ID'),
   ]
];
