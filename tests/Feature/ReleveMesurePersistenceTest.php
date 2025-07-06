<?php

use App\Models\ReleveMesure;
use Illuminate\Support\Facades\Schema;

// Validation & persistence test for ReleveMesure

test('a releve_mesure record can be persisted with minimal valid data', function () {
    // Désactiver les contraintes pour simplifier la création des FK inexistantes (environnement de test).
    Schema::disableForeignKeyConstraints();

    // Créer un utilisateur et un équipement factices
    App\Models\User::factory()->create(['id' => 1, 'email' => 'user@example.com']);
    App\Models\Equipement::create([
        'id' => 1,
        'nom' => 'Pompe test',
        'site_id' => 1,
        'modele_id' => 1,
        'type_gaz_id' => 1,
    ]);

    $data = [
        'equipement_id'      => 1,
        'user_id'            => 1,
        'type_mesure'        => 'Température',
        'valeur'             => 21.5,
        'unite'              => '°C',
        'date_mesure'        => now(),
    ];

    $releve = ReleveMesure::create($data);

    expect($releve->id)->not->toBeNull();
    $this->assertDatabaseHas('releves_mesures', [
        'id'          => $releve->id,
        'type_mesure' => 'Température',
    ]);
});