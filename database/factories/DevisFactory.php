<?php

namespace Database\Factories;

use App\Models\Devis;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Devis> */
class DevisFactory extends Factory
{
    protected $model = Devis::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'date_creation' => now(),
            'date_validite' => now()->addMonth(),
            'statut' => 'en attente',
            'montant_total' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
} 