<?php

namespace Database\Seeders;

use App\Models\StatutDemande;
use Illuminate\Database\Seeder;

class StatutDemandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatutDemande::query()->delete();

        StatutDemande::create([
            'nom' => 'En attente',
            'description' => 'Demande en attente de traitement',
        ]);

        StatutDemande::create([
            'nom' => 'En cours',
            'description' => 'Demande en cours de traitement',
        ]);

        StatutDemande::create([
            'nom' => 'Résolue',
            'description' => 'Demande résolue et fermée',
        ]);

        StatutDemande::create([
            'nom' => 'Annulée',
            'description' => 'Demande annulée',
        ]);
    }
}
