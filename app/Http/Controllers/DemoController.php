<?php

namespace App\Http\Controllers;

use App\Services\DemoService;
use Illuminate\Http\JsonResponse;

class DemoController extends Controller
{
    public function __construct(
        private DemoService $demoService
    ) {}

    public function randomStatus(): JsonResponse
    {
        return $this->demoService->randomFail();
    }

    public function alwaysOk(): JsonResponse
    {
        return response()->json($this->demoService->alwaysOk());
    }
}