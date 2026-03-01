<?php

namespace Database\Seeders;

use App\Models\TicketCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketCodeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TicketCode::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $codes     = [];
        $generated = [];
        $count     = 200;

        while (count($codes) < $count) {
            $code = strtoupper(Str::random(8));
            if (in_array($code, $generated)) continue;

            $generated[] = $code;
            $codes[] = [
                'id'         => Str::uuid(),
                'code'       => $code,
                'is_used'    => false,
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($codes, 100) as $chunk) {
            TicketCode::insert($chunk);
        }

        $this->command->info("✅ TicketCodes seeded : {$count} codes générés");
    }
}