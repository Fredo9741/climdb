<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On utilise firstOrCreate pour éviter les exceptions en cas de rôle déjà présent (tests parallèles)

        foreach (['admin', 'technicien', 'client', 'manager'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }
}
