<?php

use App\Models\{User,BouteilleGaz,TypeGaz,StatutBouteille};
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;
use function Pest\Laravel\get;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    actingAs($admin);
});

test('bottle edit form pre-fills serial number and update works', function () {
    $typeGaz = TypeGaz::factory()->create();
    $statut = StatutBouteille::factory()->create(['nom' => 'Disponible']);

    $bouteille = BouteilleGaz::factory()->create([
        'numero_serie' => 'SN-123',
        'type_gaz_id' => $typeGaz->id,
        'capacite_kg' => 10,
        'poids_actuel_kg' => 5,
        'statut_bouteille_id' => $statut->id,
    ]);

    // Vérifier que l'edit renvoie la valeur
    get(route('bouteilles-gaz.edit', $bouteille))->assertStatus(200)->assertSee('SN-123');

    // Mettre à jour (sans changer le numéro)
    put(route('bouteilles-gaz.update', $bouteille), [
        'numero_serie' => 'SN-123',
        'type_gaz_id' => $typeGaz->id,
        'capacite_kg' => 10,
        'poids_actuel_kg' => 6,
        'statut_bouteille_id' => $statut->id,
    ])->assertRedirect(route('bouteilles-gaz.index'));

    $this->assertDatabaseHas('bouteilles_gaz', [
        'id' => $bouteille->id,
        'poids_actuel_kg' => 6,
    ]);
});