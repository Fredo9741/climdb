<?php

namespace Database\Factories;

use App\Models\StatutBouteille;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<StatutBouteille> */
class StatutBouteilleFactory extends Factory
{
    protected $model = StatutBouteille::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement(['Pleine', 'Vide', 'En inspection', 'Endommagée']),
        ];
    }
} 