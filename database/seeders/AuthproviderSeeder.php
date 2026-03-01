<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthProviderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('auth_providers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('auth_providers')->insert([
            ['id' => 1, 'name' => 'local'],
            ['id' => 2, 'name' => 'google'],
            ['id' => 3, 'name' => 'facebook'],
        ]);
    }
}