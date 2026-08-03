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
    ->withMiddleware(function (Middleware $middleware): void {
        // cPanel/LiteSpeed terminates TLS in front of PHP, so without this
        // Laravel sees plain HTTP and builds http:// redirect URLs. The browser
        // then bounces http -> https, and the session cookie (which is marked
        // Secure) is not sent on that hop — so flashed success messages are lost
        // between the POST and the page it redirects to.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
