<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modele;
use App\Models\TypeEquipement;
use App\Models\TypeGaz;
use App\Models\ModeleReleve;

class ModeleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Modele::query()->delete();

        $splitType = TypeEquipement::where('nom', 'Split système')->first();
        $rooftopType = TypeEquipement::where('nom', 'Rooftop')->first();

        $r410a = TypeGaz::where('nom', 'R410A')->first();
        $r32 = TypeGaz::where('nom', 'R32')->first();

        $releveClim = ModeleReleve::where('nom', 'Relevé Standard Climatiseur')->first();
        $releveChambreFroide = ModeleReleve::where('nom', 'Relevé Chambre Froide')->first();

        Modele::create([
            'type_equipement_id' => $splitType->id,
            'marque' => 'Daikin',
            'nom' => 'FTXM50R',
            'reference_constructeur' => 'FTXM50R-2023',
            'description' => 'Climatiseur mural réversible Inverter.',
            'quantite_gaz_kg' => 1.2,
            'type_gaz_id' => $r32->id,
            'modele_releve_defaut_id' => $releveClim->id,
        ]);

        Modele::create([
            'type_equipement_id' => $splitType->id,
            'marque' => 'Mitsubishi Electric',
            'nom' => 'MSZ-AP35VGK',
            'reference_constructeur' => 'MSZ-AP35VGK-2022',
            'description' => 'Unité intérieure compacte et silencieuse.',
            'quantite_gaz_kg' => 0.8,
            'type_gaz_id' => $r32->id,
            'modele_releve_defaut_id' => $releveClim->id,
        ]);

        Modele::create([
            'type_equipement_id' => $rooftopType->id,
            'marque' => 'Frigotech',
            'nom' => 'CF-5000',
            'reference_constructeur' => 'FRG-CF5000-V2',
            'description' => 'Chambre froide positive de 50m³.',
            'quantite_gaz_kg' => 5.5,
            'type_gaz_id' => $r410a->id,
            'modele_releve_defaut_id' => $releveChambreFroide->id,
        ]);
    }
}