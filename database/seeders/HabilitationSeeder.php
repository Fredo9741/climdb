<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Habilitation;
use Illuminate\Database\Seeder;

class HabilitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilitations = [
            [
                'nom' => 'Attestation fluides Catégorie I',
                'description' => 'Attestation d’aptitude à la manipulation des fluides frigorigènes – Catégorie I (toutes opérations)'.
                    ' – obligatoire pour tout professionnel manipulant des fluides frigorigènes.'
            ],
            [
                'nom' => 'Attestation fluides Catégorie II',
                'description' => 'Attestation d’aptitude à la manipulation des fluides frigorigènes – Catégorie II.'
            ],
            [
                'nom' => 'Attestation fluides Catégorie III',
                'description' => 'Attestation d’aptitude à la manipulation des fluides frigorigènes – Catégorie III.'
            ],
            [
                'nom' => 'Attestation fluides Catégorie IV',
                'description' => 'Attestation d’aptitude à la manipulation des fluides frigorigènes – Catégorie IV.'
            ],
            [
                'nom' => 'Habilitation électrique B0/H0',
                'description' => 'Habilitation électrique NF C 18-510 – B0/H0 (non-électricien, interventions simples).'
            ],
            [
                'nom' => 'Habilitation électrique BR',
                'description' => 'Habilitation électrique NF C 18-510 – BR (dépannage ou remplacement).'
            ],
            [
                'nom' => 'Habilitation électrique BS',
                'description' => 'Habilitation électrique NF C 18-510 – BS (interventions simples dans des armoires).'
            ],
            [
                'nom' => 'Habilitation électrique BC',
                'description' => 'Habilitation électrique NF C 18-510 – BC (consignation).'
            ],
            [
                'nom' => 'CACES R482',
                'description' => 'CACES R482 – Engins de chantier (déplacement de groupes frigorifiques lourds).'
            ],
            [
                'nom' => 'CACES R489',
                'description' => 'CACES R489 – Chariots élévateurs (manutention de groupes frigorifiques).'
            ],
            [
                'nom' => 'CACES R486 Catégorie A',
                'description' => 'CACES R486 – Nacelles à élévation verticale (type ciseaux), souvent utilisées en intérieur.'
            ],
            [
                'nom' => 'CACES R486 Catégorie B',
                'description' => 'CACES R486 – Nacelles à élévation multidirectionnelle (bras articulés ou télescopiques), très utilisées en extérieur.'
            ],
            [
                'nom' => 'CACES R486 Catégorie C',
                'description' => 'CACES R486 – Conduite hors production : déplacement, entretien et maintenance des nacelles.'
            ],
            [
                'nom' => 'Travail en hauteur / port du harnais',
                'description' => 'Habilitation au travail en hauteur, port du harnais – interventions sur unités extérieures en toiture.'
            ],
            [
                'nom' => 'Montage/Démontage échafaudage roulant (R457)',
                'description' => 'Habilitation pour le montage et démontage d\'échafaudages roulants (norme R457) – petits travaux de façade, maintenance rapide.'
            ],
            [
                'nom' => 'Montage/Démontage échafaudage fixe (R408)',
                'description' => 'Habilitation pour le montage et démontage d\'échafaudages fixes (norme R408) – structures rigides pour chantiers plus longs et plus hauts.'
            ],
            [
                'nom' => 'Montage/Démontage platelage léger',
                'description' => 'Habilitation pour échafaudages de faible hauteur de type platelage léger (petits échafaudages domestiques).'
            ],
            [
                'nom' => 'Soudure oxyacétylénique (brasage cuivre)',
                'description' => 'Habilitation/ATEX brasage au chalumeau oxyacétylénique – soudure de tubes cuivre en climatisation et froid.'
            ],
            [
                'nom' => 'Soudure à l\'arc (ARC)',
                'description' => 'Habilitation soudure ARC – fixation de structures métalliques, réparations lourdes.'
            ],
            [
                'nom' => 'Soudure TIG',
                'description' => 'Habilitation soudure TIG – soudure fine (inox), utilisations ponctuelles sur installations spécifiques.'
            ],
            [
                'nom' => 'SST',
                'description' => 'Sauveteur Secouriste du Travail – premiers secours en entreprise.'
            ],
        ];

        foreach ($habilitations as $hab) {
            Habilitation::firstOrCreate(['nom' => $hab['nom']], $hab);
        }
    }
} 