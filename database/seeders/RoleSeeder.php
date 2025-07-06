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

        $roles = collect(['admin', 'technicien', 'client', 'manager'])
            ->map(fn($name) => ['name' => $name, 'guard_name' => 'web']);

        // insertOrIgnore évite l'exception RoleAlreadyExists en tests parallèles
        \DB::table('roles')->insertOrIgnore($roles->all());
    }
}
