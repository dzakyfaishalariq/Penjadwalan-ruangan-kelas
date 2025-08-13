<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // mildeware untuk akses mahasiswa
        $middleware->alias([
            'authMahasiswa.api' => \App\Http\Middleware\AuthenticateMahasiswaAPI::class,
            'authDosen.api' => \App\Http\Middleware\AuthenticateDosenAPI::class,
            'authAdmin.api' => \App\Http\Middleware\AuthenticateAdminAPI::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
