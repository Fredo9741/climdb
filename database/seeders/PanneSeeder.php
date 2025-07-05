<?php

namespace Database\Seeders;

use App\Models\Panne;
use App\Models\Equipement;
use App\Models\User;
use Illuminate\Database\Seeder;

class PanneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipements = Equipement::all();
        $users = User::all();

        if ($equipements->isEmpty() || $users->isEmpty()) {
            return;
        }

        $pannes = [
            [
                'equipement_id' => $equipements->first()->id,
                'description_panne' => 'Problème de refroidissement - la température ne descend plus en dessous de 25°C malgré un fonctionnement continu du compresseur.',
                'actions_correctives' => 'Vérification du circuit de réfrigérant en cours. Contrôle des niveaux de gaz réfrigérant nécessaire.',
                'statut_demande_id' => 2, // En cours
                'priorite' => 'haute',
                'date_constat' => '2024-01-15 09:30:00',
                'user_id' => $users->first()->id,
            ],
            [
                'equipement_id' => $equipements->skip(1)->first()->id ?? $equipements->first()->id,
                'description_panne' => 'Bruit anormal au niveau du ventilateur - vibrations importantes et sifflement continu.',
                'actions_correctives' => 'Démontage du ventilateur prévu pour inspection des roulements.',
                'statut_demande_id' => 1, // En attente
                'priorite' => 'moyenne',
                'date_constat' => '2024-01-20 14:15:00',
                'user_id' => $users->first()->id,
            ],
            [
                'equipement_id' => $equipements->skip(2)->first()->id ?? $equipements->first()->id,
                'description_panne' => 'Écran de contrôle défaillant - affichage intermittent et boutons non réactifs.',
                'actions_correctives' => 'Remplacement de l\'écran effectué. Tests de fonctionnement validés.',
                'statut_demande_id' => 3, // Résolue
                'priorite' => 'faible',
                'date_constat' => '2024-01-10 11:00:00',
                'date_resolution' => '2024-01-12 16:30:00',
                'user_id' => $users->first()->id,
            ],
            [
                'equipement_id' => $equipements->skip(3)->first()->id ?? $equipements->first()->id,
                'description_panne' => 'Fuite de gaz réfrigérant détectée au niveau des raccords - arrêt immédiat nécessaire.',
                'actions_correctives' => 'Intervention d\'urgence planifiée. Isolation du circuit en cours.',
                'statut_demande_id' => 2, // En cours
                'priorite' => 'urgente',
                'date_constat' => '2024-01-25 08:45:00',
                'user_id' => $users->first()->id,
            ],
            [
                'equipement_id' => $equipements->skip(4)->first()->id ?? $equipements->first()->id,
                'description_panne' => 'Filtre encrassé - débit d\'air réduit et surconsommation électrique observée.',
                'actions_correctives' => 'Remplacement du filtre et nettoyage des conduits effectués.',
                'statut_demande_id' => 3, // Résolue
                'priorite' => 'faible',
                'date_constat' => '2024-01-05 10:20:00',
                'date_resolution' => '2024-01-06 14:00:00',
                'user_id' => $users->first()->id,
            ],
        ];

        foreach ($pannes as $panneData) {
            Panne::create($panneData);
        }
    }
} 