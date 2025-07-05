<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipement;
use App\Models\Site;
use App\Models\Modele;

class EquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipement::query()->delete();

        $siteAlphaParis = Site::where('nom', 'Siège Social Alpha')->first();
        $siteAlphaNantes = Site::where('nom', 'Dépôt Alpha Ouest')->first();
        $siteBetaLyon = Site::where('nom', 'Agence Lyon Beta')->first();

        $modeleDaikin = Modele::where('reference_constructeur', 'FTXM50R-2023')->first();
        $modeleMitsubishi = Modele::where('reference_constructeur', 'MSZ-AP35VGK-2022')->first();
        $modeleFrigotech = Modele::where('reference_constructeur', 'FRG-CF5000-V2')->first();

        Equipement::create([
            'site_id' => $siteAlphaParis->id,
            'modele_id' => $modeleDaikin->id,
            'numero_serie' => 'DAIKIN-ABC-12345',
            'nom' => 'Clim Bureau Direction',
            'description' => 'Climatiseur installé dans le bureau du directeur.',
            'date_installation' => '2023-01-15',
            'date_derniere_maintenance' => '2024-01-15',
            'localisation_precise' => 'Bureau 101',
            'etat' => 'actif',
        ]);

        Equipement::create([
            'site_id' => $siteAlphaParis->id,
            'modele_id' => $modeleMitsubishi->id,
            'numero_serie' => 'MITSU-XYZ-67890',
            'nom' => 'Clim Salle Réunion',
            'description' => 'Climatiseur de la grande salle de réunion.',
            'date_installation' => '2023-03-20',
            'date_derniere_maintenance' => '2024-03-20',
            'localisation_precise' => 'Salle de réunion principale',
            'etat' => 'actif',
        ]);

        Equipement::create([
            'site_id' => $siteAlphaNantes->id,
            'modele_id' => $modeleFrigotech->id,
            'numero_serie' => 'FRIGO-DEPOT-001',
            'nom' => 'Chambre Froide Dépôt',
            'description' => 'Chambre froide pour le stockage des denrées périssables.',
            'date_installation' => '2022-05-10',
            'date_derniere_maintenance' => '2024-05-10',
            'localisation_precise' => 'Zone de stockage B',
            'etat' => 'actif',
        ]);

        Equipement::create([
            'site_id' => $siteBetaLyon->id,
            'modele_id' => $modeleDaikin->id,
            'numero_serie' => 'DAIKIN-LYON-54321',
            'nom' => 'Clim Accueil Agence',
            'description' => 'Climatiseur de la zone d\'accueil de l\'agence.',
            'date_installation' => '2023-08-01',
            'date_derniere_maintenance' => '2024-08-01',
            'localisation_precise' => 'Zone d\'accueil',
            'etat' => 'actif',
        ]);
    }
}