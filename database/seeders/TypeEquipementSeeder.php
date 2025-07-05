<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeEquipement;

class TypeEquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeEquipement::query()->delete();

        TypeEquipement::create(['nom' => 'Split système', 'description' => 'système de climatisation']);
        TypeEquipement::create(['nom' => 'Rooftop', 'description' => 'système de climatisation pour les bâtiments']);
        TypeEquipement::create(['nom' => 'GEG', 'description' => 'Groupe eau glacée']);
        TypeEquipement::create(['nom' => 'Ventilation', 'description' => 'système de ventilation']);
    }
}