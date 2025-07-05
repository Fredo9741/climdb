<?php

namespace Database\Seeders;

use App\Models\TypeEquipement;
use Illuminate\Database\Seeder;

class TypeEquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typesEquipement = [
            ['nom' => 'Split système', 'description' => 'système de climatisation'],
            ['nom' => 'Rooftop', 'description' => 'système de climatisation pour les bâtiments'],
            ['nom' => 'GEG', 'description' => 'Groupe eau glacée'],
            ['nom' => 'Ventilation', 'description' => 'système de ventilation'],
        ];

        foreach ($typesEquipement as $type) {
            TypeEquipement::updateOrCreate(
                ['nom' => $type['nom']],
                $type
            );
        }
    }
}
