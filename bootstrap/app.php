<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\WrapRequestInTransaction::class);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return \Illuminate\Support\Facades\Response::apiResponse(
                \App\Enums\HttpStatus::UNPROCESSABLE_ENTITY,
                $e->errors(),
                $e->getMessage(),
            );
        });

        $exceptions->render(function (AuthenticationException $e) {
            return \Illuminate\Support\Facades\Response::apiResponse(
                \App\Enums\HttpStatus::UNAUTHORIZED,
                message: $e->getMessage(),
            );
        });



    })->create();
