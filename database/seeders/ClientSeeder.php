<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::query()->delete();

        Client::create([
            'nom' => 'Entreprise Alpha',
            'adresse' => '10 Rue des Lilas',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'pays' => 'France',
            'telephone' => '0123456789',
            'email' => 'contact@alpha.com',
            'actif' => true,
        ]);

        Client::create([
            'nom' => 'Société Beta',
            'adresse' => '25 Avenue de la République',
            'ville' => 'Lyon',
            'code_postal' => '69002',
            'pays' => 'France',
            'telephone' => '0987654321',
            'email' => 'info@beta.fr',
            'actif' => true,
        ]);
    }
}
