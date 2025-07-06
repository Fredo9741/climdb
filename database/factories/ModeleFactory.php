<?php

namespace Database\Factories;

use App\Models\Modele;
use App\Models\TypeEquipement;
use App\Models\TypeGaz;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Modele> */
class ModeleFactory extends Factory
{
    protected $model = Modele::class;

    public function definition(): array
    {
        return [
            'type_equipement_id' => TypeEquipement::factory(),
            'marque' => $this->faker->company,
            'nom' => $this->faker->bothify('Model-###'),
            'reference_constructeur' => $this->faker->bothify('REF-####'),
            'type_gaz_id' => TypeGaz::factory(),
        ];
    }
} 