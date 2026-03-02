<?php

namespace App\Services;

use App\Exceptions\ParticipationException;
use App\Exceptions\RedemptionException;
use App\Models\Participation;
use App\Models\Redemption;
use App\Models\User;
use Illuminate\Support\Str;

class RedemptionService
{
    /**
     * Demander la réclamation d'un lot
     */
    public function request(User $user, string $participationId, string $method): Redemption
    {
        $participation = Participation::where('id', $participationId)
                                      ->where('user_id', $user->id)
                                      ->with('redemption')
                                      ->first();

        if (!$participation) {
            throw ParticipationException::notFound();
        }

        if (!$participation->hasWon()) {
            throw new \App\Exceptions\AppException('Cette participation ne correspond à aucun lot gagné.', 400);
        }

        if ($participation->isRedeemed()) {
            throw RedemptionException::alreadyRedeemed();
        }

        return Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $participation->id,
            'method'           => $method,
            'status'           => Redemption::STATUS_PENDING,
            'requested_at'     => now(),
        ]);
    }

    /**
     * Valider / rejeter une demande de réclamation (employee / admin)
     */
    public function updateStatus(string $redemptionId, string $status): Redemption
    {
        $redemption = Redemption::findOrFail($redemptionId);

        if (!in_array($status, [
            Redemption::STATUS_APPROVED,
            Redemption::STATUS_REJECTED,
            Redemption::STATUS_COMPLETED,
        ])) {
            throw new \App\Exceptions\AppException('Statut invalide.', 422);
        }

        $data = ['status' => $status];

        if ($status === Redemption::STATUS_COMPLETED) {
            $data['completed_at'] = now();
        }

        $redemption->update($data);

        return $redemption->fresh();
    }

    /**
     * Liste des demandes de réclamation (employee / admin)
     */
    public function getPending(int $perPage = 20)
    {
        return Redemption::where('status', Redemption::STATUS_PENDING)
                         ->with(['participation.user', 'participation.prize'])
                         ->orderBy('requested_at')
                         ->paginate($perPage);
    }
}