<?php

namespace App\Services;

class MetricsService
{
    private static $metricsFile = '/tmp/laravel_metrics.json';

    private static function loadMetrics(): array
    {
        if (file_exists(self::$metricsFile)) {
            $content = file_get_contents(self::$metricsFile);
            return json_decode($content, true) ?: [];
        }
        return [];
    }

    private static function saveMetrics(array $metrics): void
    {
        file_put_contents(self::$metricsFile, json_encode($metrics));
    }

    public static function incrementCounter(string $name, array $labels = [], int $value = 1)
    {
        $metrics = self::loadMetrics();
        $key = self::buildKey($name, $labels);
        
        if (!isset($metrics[$key])) {
            $metrics[$key] = [
                'name' => $name,
                'type' => 'counter',
                'labels' => $labels,
                'value' => 0
            ];
        }
        $metrics[$key]['value'] += $value;
        
        self::saveMetrics($metrics);
    }

    public static function observeHistogram(string $name, array $labels = [], float $value = 0)
    {
        $metrics = self::loadMetrics();
        $key = self::buildKey($name, $labels);
        
        if (!isset($metrics[$key])) {
            $metrics[$key] = [
                'name' => $name,
                'type' => 'histogram',
                'labels' => $labels,
                'values' => [],
                'count' => 0,
                'sum' => 0
            ];
        }
        $metrics[$key]['values'][] = $value;
        $metrics[$key]['count']++;
        $metrics[$key]['sum'] += $value;
        
        self::saveMetrics($metrics);
    }

    private static function buildKey(string $name, array $labels): string
    {
        ksort($labels);
        return $name . ':' . json_encode($labels);
    }

    private static function formatLabels(array $labels): string
    {
        if (empty($labels)) {
            return '';
        }
        
        $labelPairs = [];
        foreach ($labels as $key => $value) {
            $labelPairs[] = "{$key}=\"{$value}\"";
        }
        
        return '{' . implode(',', $labelPairs) . '}';
    }

    public static function export(): string
    {
        $metrics = self::loadMetrics();
        
        if (empty($metrics)) {
            return "# No metrics collected yet\n";
        }
        
        $output = [];
        $grouped = [];

        foreach ($metrics as $metric) {
            $grouped[$metric['name']][] = $metric;
        }

        foreach ($grouped as $name => $metricsList) {
            $first = $metricsList[0];
            $type = $first['type'];
            
            $output[] = "# HELP {$name} Application metric";
            $output[] = "# TYPE {$name} {$type}";
            
            foreach ($metricsList as $metric) {
                if ($type === 'histogram') {
                    $buckets = [0.005, 0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5, 10];
                    
                    foreach ($buckets as $bucket) {
                        $count = count(array_filter($metric['values'], fn($v) => $v <= $bucket));
                        $bucketLabels = array_merge($metric['labels'], ['le' => (string)$bucket]);
                        $labelStr = self::formatLabels($bucketLabels);
                        $output[] = "{$name}_bucket{$labelStr} {$count}";
                    }
                    
                    $infLabels = array_merge($metric['labels'], ['le' => '+Inf']);
                    $infLabelStr = self::formatLabels($infLabels);
                    $output[] = "{$name}_bucket{$infLabelStr} {$metric['count']}";
                    
                    $labelStr = self::formatLabels($metric['labels']);
                    $output[] = "{$name}_sum{$labelStr} {$metric['sum']}";
                    $output[] = "{$name}_count{$labelStr} {$metric['count']}";
                } else {
                    $labelStr = self::formatLabels($metric['labels']);
                    $output[] = "{$name}{$labelStr} {$metric['value']}";
                }
            }
        }

        return implode("\n", $output) . "\n";
    }
    
    public static function reset(): void
    {
        if (file_exists(self::$metricsFile)) {
            unlink(self::$metricsFile);
        }
    }
}