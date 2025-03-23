<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler le seeder pour les rôles et permissions (Spatie)
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Crée un utilisateur de test (vous pouvez adapter ou ajouter d'autres utilisateurs)
        User::factory()->create([
            'name'  => 'test',
            'email' => 'test@example.com',
        ]);
    }
}
