<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'mitra' => \App\Http\Middleware\MitraMiddleware::class,
            'admin_or_mitra' => \App\Http\Middleware\AdminOrMitraMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (Throwable $throwable, $request) {
            if ($throwable instanceof NotFoundHttpException) {
                return response()->view('404', [], 404);
            }

            return null;

        });
        
    })->create();
