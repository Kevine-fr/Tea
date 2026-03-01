<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RedemptionRequest;
use App\Http\Requests\UpdateRedemptionStatusRequest;
use App\Http\Resources\RedemptionResource;
use App\Services\RedemptionService;
use Illuminate\Http\JsonResponse;

class RedemptionController extends Controller
{
    public function __construct(protected RedemptionService $service) {}

    /**
     * POST /api/redemptions
     * Demander la réclamation d'un lot
     */
    public function store(RedemptionRequest $request): JsonResponse
    {
        $redemption = $this->service->request(
            $request->user(),
            $request->participation_id,
            $request->method
        );

        return response()->json([
            'success' => true,
            'message' => 'Votre demande de réclamation a été soumise.',
            'data'    => new RedemptionResource($redemption),
        ], 201);
    }

    /**
     * GET /api/admin/redemptions
     * Liste des demandes en attente (employee / admin)
     */
    public function pending(): JsonResponse
    {
        $redemptions = $this->service->getPending();

        return response()->json([
            'success' => true,
            'data'    => RedemptionResource::collection($redemptions),
        ]);
    }

    /**
     * PATCH /api/admin/redemptions/{id}/status
     * Mettre à jour le statut (employee / admin)
     */
    public function updateStatus(UpdateRedemptionStatusRequest $request, string $id): JsonResponse
    {
        $redemption = $this->service->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour.',
            'data'    => new RedemptionResource($redemption),
        ]);
    }
}