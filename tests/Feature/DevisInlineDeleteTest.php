<?php

use App\Models\{User,Client,Devis};
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    actingAs($admin);
});

test('inline deletion removes devis from database', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'numero' => 'DEV-TEST',
        'date_creation' => today(),
        'date_validite' => today()->addDays(30),
    ]);

    delete(route('devis.destroy', $devis))->assertRedirect('/devis');

    $this->assertDatabaseMissing('devis', ['id' => $devis->id]);
});