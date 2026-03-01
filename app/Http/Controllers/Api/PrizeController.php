<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrizeResource;
use App\Models\Prize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrizeController extends Controller
{
    /**
     * GET /api/prizes
     */
    public function index(): JsonResponse
    {
        $prizes = Prize::all();
        return response()->json([
            'success' => true,
            'data'    => PrizeResource::collection($prizes),
        ]);
    }

    /**
     * POST /api/admin/prizes
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        $prize = Prize::create(['id' => Str::uuid(), ...$validated]);

        return response()->json([
            'success' => true,
            'data'    => new PrizeResource($prize),
        ], 201);
    }

    /**
     * PUT /api/admin/prizes/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $prize = Prize::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['sometimes', 'integer', 'min:0'],
        ]);

        $prize->update($validated);

        return response()->json([
            'success' => true,
            'data'    => new PrizeResource($prize),
        ]);
    }

    /**
     * DELETE /api/admin/prizes/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return response()->json(['success' => true, 'message' => 'Lot supprimé.']);
    }
}