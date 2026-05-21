<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'track-visit' => \App\Http\Middleware\TrackVisit::class,
        ]);

        // append ke web group supaya tracking otomatis di semua halaman frontend
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
