<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $woofer = Role::create(['name' => 'woofer']);

        // Définition des permissions
        Permission::create(['name' => 'manage users']); // Gérer les utilisateurs
        Permission::create(['name' => 'manage workshops']); // Gérer les ateliers
        Permission::create(['name' => 'assign woofers']); // Assigner des woofers aux ateliers
        Permission::create(['name' => 'manage stock']); // Gérer le stock
        Permission::create(['name' => 'manage sales']); // Gérer les ventes
        Permission::create(['name' => 'record sales']); // Woofer enregistre les ventes
        Permission::create(['name' => 'view tasks']);   // Woofer voit ses tâches
        Permission::create(['name' => 'modify stock']); // Woofer peut modifier le stock

        // Attribution des permissions aux rôles
        $admin->givePermissionTo(['manage users', 'manage workshops', 'assign woofers', 'manage stock', 'manage sales']);
        $woofer->givePermissionTo(['record sales', 'view tasks', 'modify stock']);
    }
}
