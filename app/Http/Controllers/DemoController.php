<?php

namespace App\Http\Controllers;

use App\Services\DemoService;
use Illuminate\Http\JsonResponse;

class DemoController extends Controller
{
    private DemoService $demoService;

    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Test de la fonction aléatoire
     */
    public function random(): JsonResponse
    {
        $result = $this->demoService->randomFail();

        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 500);
        }

        return response()->json([
            'message' => $result['message']
        ], 200);
    }

    /**
     * Test de la fonction toujours OK
     */
    public function always(): JsonResponse
    {
        $result = $this->demoService->alwaysOk();

        return response()->json($result, 200);
    }
}
