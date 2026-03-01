<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParticipateRequest;
use App\Http\Resources\ParticipationResource;
use App\Services\ParticipationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    public function __construct(protected ParticipationService $service) {}

    /**
     * POST /api/participations
     * Soumettre un code ticket
     */
    public function store(ParticipateRequest $request): JsonResponse
    {
        $participation = $this->service->participate(
            $request->user(),
            $request->code
        );

        $won = $participation->hasWon();

        return response()->json([
            'success' => true,
            'message' => $won
                ? "Félicitations ! Vous avez gagné : {$participation->prize->name} 🎉"
                : 'Merci pour votre participation ! Bonne chance pour la prochaine fois.',
            'data'    => new ParticipationResource($participation),
        ], 201);
    }

    /**
     * GET /api/participations
     * Historique de l'utilisateur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $participations = $this->service->getUserParticipations($request->user());

        return response()->json([
            'success' => true,
            'data'    => ParticipationResource::collection($participations),
            'meta'    => [
                'total'        => $participations->total(),
                'current_page' => $participations->currentPage(),
                'last_page'    => $participations->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/admin/participations
     * Toutes les participations (admin / employee)
     */
    public function adminIndex(): JsonResponse
    {
        $participations = $this->service->getAllParticipations();

        return response()->json([
            'success' => true,
            'data'    => ParticipationResource::collection($participations),
            'meta'    => [
                'total'        => $participations->total(),
                'current_page' => $participations->currentPage(),
                'last_page'    => $participations->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/admin/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->service->getStats(),
        ]);
    }
}