<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Site::query()->delete();

        $clientAlpha = Client::where('nom', 'Entreprise Alpha')->first();
        $clientBeta = Client::where('nom', 'Société Beta')->first();

        Site::create([
            'client_id' => $clientAlpha->id,
            'nom' => 'Siège Social Alpha',
            'adresse' => '10 Rue des Lilas',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'pays' => 'France',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        Site::create([
            'client_id' => $clientAlpha->id,
            'nom' => 'Dépôt Alpha Ouest',
            'adresse' => '50 Chemin de l\'Usine',
            'ville' => 'Nantes',
            'code_postal' => '44000',
            'pays' => 'France',
            'latitude' => 47.2184,
            'longitude' => -1.5536,
        ]);

        Site::create([
            'client_id' => $clientBeta->id,
            'nom' => 'Agence Lyon Beta',
            'adresse' => '25 Avenue de la République',
            'ville' => 'Lyon',
            'code_postal' => '69002',
            'pays' => 'France',
            'latitude' => 45.7578,
            'longitude' => 4.8320,
        ]);
    }
}
