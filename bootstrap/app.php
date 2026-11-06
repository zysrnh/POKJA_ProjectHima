<?php

use App\Http\Middleware\SecurityMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->web(append: [
            SecurityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Render halaman custom 419 saat CSRF Token Mismatch
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi keamanan telah kedaluwarsa. Silakan muat ulang halaman.',
                ], 419);
            }
            return response()->view('errors.419', ['exception' => $e], 419);
        });

        // Cegah kebocoran Stack Trace / Debug Info pada error 500 ke publik
        $exceptions->render(function (Throwable $e, Request $request) {
            if (
                $e instanceof ValidationException ||
                $e instanceof AuthenticationException ||
                $e instanceof HttpExceptionInterface
            ) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi gangguan pada server internal.',
                ], 500);
            }

            return response()->view('errors.500', ['exception' => $e], 500);
        });
    })->create();
