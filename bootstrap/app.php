<?php

use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $exception) {
            return ApiResponse::error('You are not allowed to perform this action.', 403);
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return ApiResponse::error('Unauthenticated.', 401);
        });

        $exceptions->render(function (ModelNotFoundException $exception) {
            return ApiResponse::error('Resource not found.', 404);
        });

        $exceptions->render(function (\Throwable $exception) {
            if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 403) {
                return ApiResponse::error('You are not allowed to perform this action.', 403);
            }

            if (app()->environment('local')) {
                return null;
            }

            return ApiResponse::error('Internal server error.', 500);
        });
    })->create();
