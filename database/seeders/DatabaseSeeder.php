<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Démarrage du seeding...');
        $this->command->newLine();

        $this->call([
            RoleSeeder::class,           // 1. Rôles (requis par users)
            AuthProviderSeeder::class,   // 2. Providers OAuth (requis par user_auths)
            UserSeeder::class,           // 3. Utilisateurs
            PrizeSeeder::class,          // 4. Lots à gagner
            TicketCodeSeeder::class,     // 5. Codes tickets
        ]);

        $this->command->newLine();
        $this->command->info('✅ Seeding terminé avec succès !');
        $this->command->newLine();
        $this->command->table(
            ['Compte', 'Email', 'Mot de passe'],
            [
                ['Admin',    'admin@thetiptop.fr',   'Admin@1234'],
                ['Employé',  'employe@thetiptop.fr', 'Employe@1234'],
                ['User',     'alice@example.com',    'User@1234'],
            ]
        );
    }
}