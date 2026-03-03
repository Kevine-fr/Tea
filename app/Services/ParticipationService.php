<?php

namespace App\Services;

use App\Exceptions\ParticipationException;
use App\Exceptions\PrizeException;
use App\Exceptions\TicketCodeException;
use App\Models\Participation;
use App\Models\Prize;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParticipationService
{
    /**
     * Soumettre un code ticket et créer la participation.
     * Attribue automatiquement un lot si disponible.
     */
    public function participate(User $user, string $code): Participation
    {
        // Pre-checks hors transaction pour éviter les problèmes de SAVEPOINT MySQL
        $ticket = TicketCode::where('code', $code)->first();
        if (!$ticket) {
            throw TicketCodeException::notFound();
        }
        if ($ticket->is_used) {
            throw TicketCodeException::alreadyUsed();
        }
        $existing = Participation::where('ticket_code_id', $ticket->id)->exists();
        if ($existing) {
            throw ParticipationException::alreadyParticipated();
        }

        return DB::transaction(function () use ($user, $code) {
            // Re-fetch avec lock pour éviter les race conditions
            $ticket = TicketCode::where('code', $code)
                                ->lockForUpdate()
                                ->first();

            // 3. Attribuer un lot aléatoirement (si disponible)
            $prize = Prize::where('stock', '>', 0)
                          ->lockForUpdate()
                          ->inRandomOrder()
                          ->first();

            $prizeId = null;
            if ($prize) {
                $prize->decrementStock();
                $prizeId = $prize->id;
            }

            // 4. Marquer le ticket comme utilisé
            $ticket->markAsUsed();

            // 5. Créer la participation
            $participation = Participation::create([
                'id'               => Str::uuid(),
                'user_id'          => $user->id,
                'ticket_code_id'   => $ticket->id,
                'prize_id'         => $prizeId,
                'participation_date' => now(),
            ]);

            return $participation->load(['ticketCode', 'prize']);
        });
    }

    /**
     * Historique des participations d'un utilisateur
     */
    public function getUserParticipations(User $user, int $perPage = 15)
    {
        return $user->participations()
                    ->with(['prize', 'redemption'])
                    ->orderBy('participation_date', 'desc')
                    ->paginate($perPage);
    }

    /**
     * Toutes les participations (admin)
     */
    public function getAllParticipations(int $perPage = 20)
    {
        return Participation::with(['user', 'prize', 'ticketCode', 'redemption'])
                            ->orderBy('participation_date', 'desc')
                            ->paginate($perPage);
    }

    /**
     * Statistiques globales (admin / employee)
     */
    public function getStats(): array
    {
        return [
            'total_participations'   => Participation::count(),
            'total_winners'          => Participation::whereNotNull('prize_id')->count(),
            'prizes_remaining_stock' => Prize::sum('stock'),
            'total_redemptions'      => \App\Models\Redemption::count(),
        ];
    }
}