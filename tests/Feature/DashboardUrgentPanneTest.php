<?php

use App\Models\User;
use App\Models\Site;
use App\Models\Equipement;
use App\Models\Panne;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    actingAs($admin);
});

test('urgent panne appears on dashboard', function () {
    $site = Site::factory()->create();
    $equipement = Equipement::factory()->create(['site_id' => $site->id]);

    Panne::factory()->create([
        'equipement_id' => $equipement->id,
        'description_panne' => 'Compresseur HS',
        'priorite' => 'urgente',
        'statut_demande_id' => 1, // en attente
        'date_constat' => now(),
    ]);

    get('/dashboard')->assertStatus(200)->assertSee('Compresseur HS');
});