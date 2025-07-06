<?php

namespace Database\Factories;

use App\Models\BouteilleGaz;
use App\Models\TypeGaz;
use App\Models\StatutBouteille;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<BouteilleGaz> */
class BouteilleGazFactory extends Factory
{
    protected $model = BouteilleGaz::class;

    public function definition(): array
    {
        return [
            'numero_serie' => 'SN-'.$this->faker->unique()->numerify('######'),
            'type_gaz_id' => TypeGaz::factory(),
            'capacite_kg' => 10,
            'poids_actuel_kg' => 5,
            'statut_bouteille_id' => StatutBouteille::factory(),
        ];
    }
} 