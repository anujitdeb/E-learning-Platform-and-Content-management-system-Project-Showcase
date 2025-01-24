<?php


return [
   'client_id' => env('SSO_CLIENT_ID', ''),
   'client_secret' => env('SSO_CLIENT_SECRET', ''),
   'redirect_uri' => env('SSO_REDIRECT_URI', ''),
   'app_uri' => env('SSO_APP_URI', 'http://127.0.0.1:8000'),
];
