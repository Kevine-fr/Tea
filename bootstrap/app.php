<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);
        $middleware->api(append: [
            \App\Http\Middleware\CollectMetrics::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json(['success' => false, 'message' => 'Les données fournies sont invalides.', 'errors' => $e->errors()], 422);
                }
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json(['success' => false, 'message' => 'Non authentifié.'], 401);
                }
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json(['success' => false, 'message' => class_basename($e->getModel()) . ' introuvable.'], 404);
                }
                if ($e instanceof \App\Exceptions\AppException) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 400);
                }
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    return response()->json(['success' => false, 'message' => $e->getMessage() ?: 'Erreur HTTP.'], $e->getStatusCode());
                }
            }
            return null;
        });
    })->create();