<?php

namespace Database\Factories;

use App\Models\TypeGaz;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TypeGaz> */
class TypeGazFactory extends Factory
{
    protected $model = TypeGaz::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement(['R32', 'R410A', 'R134a']).$this->faker->unique()->randomNumber(3),
            'formule_chimique' => null,
            'description' => $this->faker->sentence(),
        ];
    }
} 