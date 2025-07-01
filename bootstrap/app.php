<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Middleware\RoleCheck;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      $middleware->append(UpdateLastSeen::class);
        $middleware->alias([
            'role.admin' => RoleCheck::class,
        ]);
    //   \Log::info('UpdateLastSeen middleware running.');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
