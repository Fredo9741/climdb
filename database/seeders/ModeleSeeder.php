<?php

namespace Database\Seeders;

use App\Models\Modele;
use App\Models\ModeleReleve;
use App\Models\TypeEquipement;
use App\Models\TypeGaz;
use Illuminate\Database\Seeder;

class ModeleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Éviter de supprimer les modèles existants car ils peuvent être référencés par des équipements
        // Modele::query()->delete();

        $splitType = TypeEquipement::where('nom', 'Split système')->first();
        $rooftopType = TypeEquipement::where('nom', 'Rooftop')->first();

        $r410a = TypeGaz::where('nom', 'R410A')->first();
        $r32 = TypeGaz::where('nom', 'R32')->first();

        $releveClim = ModeleReleve::where('nom', 'Maintenance Préventive Climatisation')->first();
        $releveChambreFroide = ModeleReleve::where('nom', 'Diagnostic de Panne Climatisation')->first();

        Modele::updateOrCreate(
            ['reference_constructeur' => 'FTXM50R-2023'],
            [
                'type_equipement_id' => $splitType->id,
                'marque' => 'Daikin',
                'nom' => 'FTXM50R',
                'description' => 'Climatiseur mural réversible Inverter.',
                'quantite_gaz_kg' => 1.2,
                'type_gaz_id' => $r32->id,
                'modele_releve_defaut_id' => $releveClim->id,
            ]
        );

        Modele::updateOrCreate(
            ['reference_constructeur' => 'MSZ-AP35VGK-2022'],
            [
                'type_equipement_id' => $splitType->id,
                'marque' => 'Mitsubishi Electric',
                'nom' => 'MSZ-AP35VGK',
                'description' => 'Unité intérieure compacte et silencieuse.',
                'quantite_gaz_kg' => 0.8,
                'type_gaz_id' => $r32->id,
                'modele_releve_defaut_id' => $releveClim->id,
            ]
        );

        Modele::updateOrCreate(
            ['reference_constructeur' => 'FRG-CF5000-V2'],
            [
                'type_equipement_id' => $rooftopType->id,
                'marque' => 'Frigotech',
                'nom' => 'CF-5000',
                'description' => 'Chambre froide positive de 50m³.',
                'quantite_gaz_kg' => 5.5,
                'type_gaz_id' => $r410a->id,
                'modele_releve_defaut_id' => $releveChambreFroide->id,
            ]
        );
    }
}
