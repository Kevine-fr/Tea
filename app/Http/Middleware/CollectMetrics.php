<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MetricsService;
use Symfony\Component\HttpFoundation\Response;

class CollectMetrics
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        $path = $this->normalizePath($request->path());
        
        MetricsService::incrementCounter('http_requests_total', [
            'method' => $request->method(),
            'path' => $path,
            'status' => (string) $response->status()
        ]);
        
        MetricsService::observeHistogram('http_request_duration_seconds', [
            'method' => $request->method(),
            'path' => $path
        ], $duration);
        
        return $response;
    }
    
    private function normalizePath(string $path): string
    {
        $path = preg_replace('/\/\d+/', '/{id}', $path);
        
        if (strlen($path) > 50) {
            $path = substr($path, 0, 47) . '...';
        }
        
        return $path ?: '/';
    }
}