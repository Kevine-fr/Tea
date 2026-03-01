<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage dans routes : ->middleware('role:admin')
     *                  ou ->middleware('role:admin,employee')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.',
            ], 401);
        }

        $user->loadMissing('role');

        if (!in_array($user->role->name, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Droits insuffisants.',
            ], 403);
        }

        return $next($request);
    }
}