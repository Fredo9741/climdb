<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Doit être avant UserSeeder
            UserSeeder::class,
            HabilitationSeeder::class,
            QualificationSeeder::class,

 //           ClientSeeder::class, // Doit être avant SiteSeeder
 //           TypeEquipementSeeder::class, // Doit être avant ModeleSeeder
 //           TypeGazSeeder::class, // Doit être avant ModeleSeeder
 //           ModeleReleveSeeder::class, // Doit être avant ModeleSeeder
 //           StatutDemandeSeeder::class, // Doit être avant PanneSeeder

 //           SiteSeeder::class, // Dépend de Client
 //           ModeleSeeder::class, // Dépend de TypeEquipement, TypeGaz, ModeleReleve

 //           EquipementSeeder::class, // Dépend de Site et Modele
 //           PanneSeeder::class, // Dépend de StatutDemande

            // Seeder TDF complet (remplace les 4 anciens seeders TDF)
 //           TdfSeeder::class, // Client TDF complet avec sites, modèles et équipements
        ]);
    }
}
