<?php

namespace Database\Seeders;

use App\Models\Prize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrizeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Prize::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $prizes = [
            [
                'name'        => 'Infuseur à thé',
                'description' => 'Infuseur en inox de qualité premium pour une infusion parfaite.',
                'stock'       => 50,
            ],
            [
                'name'        => 'Boîte de 100g de thé détox',
                'description' => 'Mélange de plantes bio pour une détox naturelle.',
                'stock'       => 100,
            ],
            [
                'name'        => 'Boîte de 100g de thé signature',
                'description' => 'Le thé exclusif Thé Tip Top, récolte 2024.',
                'stock'       => 80,
            ],
            [
                'name'        => 'Coffret découverte (39€)',
                'description' => 'Sélection de 5 thés d\'exception pour découvrir nos saveurs.',
                'stock'       => 30,
            ],
            [
                'name'        => 'Coffret prestige (69€)',
                'description' => 'Notre coffret haut de gamme avec 10 thés rares et un guide de dégustation.',
                'stock'       => 15,
            ],
        ];

        foreach ($prizes as $prize) {
            Prize::create(['id' => Str::uuid(), ...$prize]);
        }

        $this->command->info('✅ Prizes seeded : ' . count($prizes) . ' lots');
    }
}