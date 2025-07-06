<?php

namespace Database\Factories;

use App\Models\Equipement;
use App\Models\Modele;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Equipement> */
class EquipementFactory extends Factory
{
    protected $model = Equipement::class;

    public function definition(): array
    {
        return [
            'numero_serie' => 'SN-'.$this->faker->unique()->numerify('#####'),
            'nom' => $this->faker->word.' equip',
            'site_id' => Site::factory(),
            'modele_id' => Modele::factory(),
        ];
    }
} 