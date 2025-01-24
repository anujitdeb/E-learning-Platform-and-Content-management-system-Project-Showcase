<?php

use App\Http\Middleware\Authentication\AdminAuthMiddleware;
use App\Http\Middleware\Authentication\UserAuthMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/v1/')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('api/v1/user')
                ->middleware('api')
                ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'user' => UserAuthMiddleware::class,
            'admin' => AdminAuthMiddleware::class,
            'scope' => CheckForAnyScope::class,
            'permission' => PermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 404) {
                return errorResponse("Data not found!", 404);
            }

            return $response;
        });
    })->create();
