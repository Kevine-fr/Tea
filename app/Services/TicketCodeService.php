<?php

namespace App\Services;

use App\Models\TicketCode;
use Illuminate\Support\Str;

class TicketCodeService
{
    /**
     * Générer des codes tickets en masse (admin)
     */
    public function generateBatch(int $quantity): int
    {
        $codes = [];
        $created = 0;

        for ($i = 0; $i < $quantity; $i++) {
            $code = strtoupper(Str::random(10));

            // Éviter les doublons
            if (TicketCode::where('code', $code)->exists()) {
                continue;
            }

            $codes[] = [
                'id'         => Str::uuid(),
                'code'       => $code,
                'is_used'    => false,
                'created_at' => now(),
            ];
            $created++;

            // Insérer par batch de 500
            if (count($codes) >= 500) {
                TicketCode::insert($codes);
                $codes = [];
            }
        }

        if (!empty($codes)) {
            TicketCode::insert($codes);
        }

        return $created;
    }

    /**
     * Importer des codes depuis un tableau (CSV / Excel)
     */
    public function importFromArray(array $codes): array
    {
        $imported = 0;
        $skipped  = 0;

        foreach ($codes as $code) {
            $code = trim(strtoupper($code));
            if (!$code) continue;

            $exists = TicketCode::where('code', $code)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            TicketCode::create([
                'id'      => Str::uuid(),
                'code'    => $code,
                'is_used' => false,
            ]);
            $imported++;
        }

        return ['imported' => $imported, 'skipped' => $skipped];
    }

    /**
     * Stats des tickets
     */
    public function getStats(): array
    {
        return [
            'total'    => TicketCode::count(),
            'used'     => TicketCode::where('is_used', true)->count(),
            'available' => TicketCode::where('is_used', false)->count(),
        ];
    }
}