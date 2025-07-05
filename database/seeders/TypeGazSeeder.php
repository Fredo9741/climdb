<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeGaz;

class TypeGazSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeGaz::query()->delete();

        TypeGaz::create(['nom' => 'R410A', 'formule_chimique' => 'CH2F2/CHF2CF3', 'description' => 'Mélange zéotropique de HFC.']);
        TypeGaz::create(['nom' => 'R32', 'formule_chimique' => 'CH2F2', 'description' => 'Fluide frigorigène pur.']);
        TypeGaz::create(['nom' => 'R407C', 'formule_chimique' => 'R125/R134a/R32', 'description' => 'Mélange de HFC.']);
        TypeGaz::create(['nom' => 'R290', 'formule_chimique' => 'C3H8', 'description' => 'Propane, fluide naturel.']);
    }
}