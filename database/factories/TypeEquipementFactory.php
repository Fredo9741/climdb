<?php

namespace Database\Factories;

use App\Models\TypeEquipement;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TypeEquipement> */
class TypeEquipementFactory extends Factory
{
    protected $model = TypeEquipement::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
} 