<?php

namespace Database\Seeders;

use App\Models\AuthProvider;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les FK pour pouvoir truncate sans erreur
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UserAuth::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─── Admin ───────────────────────────────────────────────────────────
        $admin = User::create([
            'id'            => Str::uuid(),
            'email'         => 'admin@thetiptop.fr',
            'password_hash' => Hash::make('Admin@1234'),
            'birth_date'    => '1990-01-15',
            'role_id'       => 1,
        ]);
        $this->createLocalAuth($admin);

        // ─── Employee ────────────────────────────────────────────────────────
        $employee = User::create([
            'id'            => Str::uuid(),
            'email'         => 'employe@thetiptop.fr',
            'password_hash' => Hash::make('Employe@1234'),
            'birth_date'    => '1995-06-20',
            'role_id'       => 2,
        ]);
        $this->createLocalAuth($employee);

        // ─── 10 utilisateurs classiques ──────────────────────────────────────
        $users = [
            ['email' => 'alice@example.com',  'birth_date' => '1992-03-10'],
            ['email' => 'bob@example.com',     'birth_date' => '1988-07-22'],
            ['email' => 'claire@example.com',  'birth_date' => '1997-11-05'],
            ['email' => 'david@example.com',   'birth_date' => '1985-02-14'],
            ['email' => 'emma@example.com',    'birth_date' => '2000-09-30'],
            ['email' => 'felix@example.com',   'birth_date' => '1993-04-18'],
            ['email' => 'grace@example.com',   'birth_date' => '1999-12-25'],
            ['email' => 'hugo@example.com',    'birth_date' => '1991-08-03'],
            ['email' => 'iris@example.com',    'birth_date' => '1996-05-17'],
            ['email' => 'julien@example.com',  'birth_date' => '1987-10-09'],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'id'            => Str::uuid(),
                'email'         => $data['email'],
                'password_hash' => Hash::make('User@1234'),
                'birth_date'    => $data['birth_date'],
                'role_id'       => 3,
            ]);
            $this->createLocalAuth($user);
        }

        $this->command->info('✅ Users seeded : admin, employee + 10 users');
    }

    private function createLocalAuth(User $user): void
    {
        UserAuth::create([
            'id'               => Str::uuid(),
            'user_id'          => $user->id,
            'provider_id'      => AuthProvider::LOCAL,
            'provider_user_id' => $user->id,
        ]);
    }
}