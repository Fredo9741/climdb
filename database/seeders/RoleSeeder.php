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
        // Supprime les rôles existants pour éviter les doublons lors des ré-exécutions
        Role::query()->delete();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'technicien', 'guard_name' => 'web']);
        Role::create(['name' => 'client', 'guard_name' => 'web']);
        Role::create(['name' => 'manager', 'guard_name' => 'web']);
    }
}