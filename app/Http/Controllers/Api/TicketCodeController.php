<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateTicketsRequest;
use App\Services\TicketCodeService;
use Illuminate\Http\JsonResponse;

class TicketCodeController extends Controller
{
    public function __construct(protected TicketCodeService $service) {}

    /**
     * POST /api/admin/tickets/generate
     */
    public function generate(GenerateTicketsRequest $request): JsonResponse
    {
        $created = $this->service->generateBatch($request->quantity);

        return response()->json([
            'success' => true,
            'message' => "{$created} codes tickets générés avec succès.",
            'data'    => ['created' => $created],
        ]);
    }

    /**
     * GET /api/admin/tickets/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->service->getStats(),
        ]);
    }
}