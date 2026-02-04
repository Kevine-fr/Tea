<?php

return [
    'namespace' => env('PROMETHEUS_NAMESPACE', 'app'),
    
    'metrics_route_enabled' => true,
    'metrics_route_path' => 'metrics',
    'metrics_route_middleware' => null,
    
    'storage_adapter' => env('PROMETHEUS_STORAGE_ADAPTER', 'memory'),
    
    'redis' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
        'timeout' => 0.1,
        'read_timeout' => 10,
        'persistent_connections' => false,
    ],
];