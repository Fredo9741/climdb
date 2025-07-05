<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprime les utilisateurs existants pour éviter les doublons lors des ré-exécutions
        User::query()->delete();

        // Créer un administrateur
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        // Créer un technicien
        $technicien = User::create([
            'name' => 'Technicien User',
            'email' => 'tech@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $techRole = Role::where('name', 'technicien')->first();
        if ($techRole) {
            $technicien->assignRole($techRole);
        }

        // Créer un utilisateur client
        $clientUser = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $clientRole = Role::where('name', 'client')->first();
        if ($clientRole) {
            $clientUser->assignRole($clientRole);
        }
    }
}
