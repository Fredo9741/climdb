<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        $qualifications = [
            [
                'nom' => 'Aide-frigoriste',
                'description' => 'Débutant, aide à l’installation, nettoyage, manutention, travaille sous supervision.'
            ],
            [
                'nom' => 'Apprenti frigoriste',
                'description' => 'Contrat d’apprentissage (CAP, Bac Pro). Formation terrain encadrée.'
            ],
            [
                'nom' => 'Technicien Frigoriste',
                'description' => 'Installation, mise en service, maintenance et dépannage des équipements frigorifiques/climatisation.'
            ],
            [
                'nom' => 'Technicien confirmé / spécialisé',
                'description' => 'Frigoriste expérimenté avec spécialisation (froid industriel, commercial, VRV/PAC, régulation, etc.).'
            ],
            [
                'nom' => 'Chef d’équipe / chef de chantier',
                'description' => 'Organise le chantier, coordonne les techniciens, assure le lien client.'
            ],
            [
                'nom' => 'Technicien SAV / Maintenance',
                'description' => 'Spécialiste du dépannage et de l’entretien, diagnostics et réparations.'
            ],
            [
                'nom' => 'Technicien bureau d\'études / chargé d\'affaires',
                'description' => 'Dimensionnement, devis, gestion de projet, suivi client.'
            ],
            [
                'nom' => 'Formateur technique / référent',
                'description' => 'Expert chargé de la formation interne et amélioration des pratiques.'
            ],
        ];

        DB::table('qualifications')->insertOrIgnore($qualifications);
    }
} 