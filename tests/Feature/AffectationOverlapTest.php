<?php

use App\Models\AffectationVehicule;
use App\Rules\NoOverlappingAffectation;

// Test de la règle d'interdiction de chevauchement des affectations

test('no overlapping affectations for a vehicle', function () {
    // Désactiver les contraintes pour l'environnement sqlite
    \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

    // Créer un utilisateur et un véhicule factices
    App\Models\User::factory()->create(['id' => 1, 'email' => 'test@example.com']);
    App\Models\Vehicule::create([
        'id' => 1,
        'marque' => 'Renault',
        'modele' => 'Kangoo',
        'immatriculation' => 'AA-123-AA',
        'annee_fabrication' => 2020,
        'kilometrage_actuel' => 0,
        'type_carburant' => 'Diesel',
        'date_acquisition' => now(),
        'statut_vehicule_id' => 1,
    ]);
    // Créons un véhicule 1 et deux affectations qui se chevauchent
    $vehiculeId = 1;

    AffectationVehicule::create([
        'vehicule_id' => $vehiculeId,
        'user_id'     => 1,
        'date_debut'  => '2025-07-01 00:00:00',
        'date_fin'    => '2025-07-10 00:00:00',
        'motif'       => 'Test',
    ]);

    $rule = new NoOverlappingAffectation($vehiculeId);

    $data = [
        'affectations' => [
            [
                'date_debut' => '2025-07-05 00:00:00',
                'date_fin'   => '2025-07-15 00:00:00',
            ],
        ],
    ];
    $rule->setData($data);

    // Devrait échouer (retour false)
    $this->assertFalse($rule->passes('affectations.0.date_debut', '2025-07-05 00:00:00'));
});