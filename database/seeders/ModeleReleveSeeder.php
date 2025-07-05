<?php

namespace Database\Seeders;

use App\Models\ElementModeleReleve;
use App\Models\ModeleReleve;
use Illuminate\Database\Seeder;

class ModeleReleveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les données existantes
        ElementModeleReleve::query()->delete();
        ModeleReleve::query()->delete();

        // 1. Modèle de relevé pour maintenance préventive de climatisation
        $modeleMaintenanceClim = ModeleReleve::create([
            'nom' => 'Maintenance Préventive Climatisation',
            'description' => 'Modèle de relevé standard pour la maintenance préventive des systèmes de climatisation split',
        ]);

        $elementsMaintenanceClim = [
            [
                'type_mesure' => 'Température Soufflage',
                'unite_attendue' => '°C',
                'emplacement_suggerer' => 'Sortie unité intérieure',
                'valeur_min_attendue' => 12.0,
                'valeur_max_attendue' => 18.0,
                'obligatoire' => true,
                'ordre' => 1,
                'commentaire_guide' => 'Mesurer la température de l\'air sortant de l\'unité intérieure après 15 minutes de fonctionnement',
            ],
            [
                'type_mesure' => 'Température Reprise',
                'unite_attendue' => '°C',
                'emplacement_suggerer' => 'Entrée unité intérieure',
                'valeur_min_attendue' => 22.0,
                'valeur_max_attendue' => 28.0,
                'obligatoire' => true,
                'ordre' => 2,
                'commentaire_guide' => 'Mesurer la température de l\'air entrant dans l\'unité intérieure',
            ],
            [
                'type_mesure' => 'Pression Haute Pression (HP)',
                'unite_attendue' => 'bar',
                'emplacement_suggerer' => 'Sortie compresseur',
                'valeur_min_attendue' => 15.0,
                'valeur_max_attendue' => 25.0,
                'obligatoire' => true,
                'ordre' => 3,
                'commentaire_guide' => 'Mesurer la pression haute pression au niveau du compresseur',
            ],
            [
                'type_mesure' => 'Pression Basse Pression (BP)',
                'unite_attendue' => 'bar',
                'emplacement_suggerer' => 'Entrée compresseur',
                'valeur_min_attendue' => 4.0,
                'valeur_max_attendue' => 7.0,
                'obligatoire' => true,
                'ordre' => 4,
                'commentaire_guide' => 'Mesurer la pression basse pression au niveau du compresseur',
            ],
            [
                'type_mesure' => 'Intensité Électrique',
                'unite_attendue' => 'A',
                'emplacement_suggerer' => 'Alimentation unité extérieure',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => false,
                'ordre' => 5,
                'commentaire_guide' => 'Mesurer l\'intensité électrique consommée par l\'unité extérieure',
            ],
            [
                'type_mesure' => 'Tension Électrique',
                'unite_attendue' => 'V',
                'emplacement_suggerer' => 'Alimentation unité extérieure',
                'valeur_min_attendue' => 220.0,
                'valeur_max_attendue' => 240.0,
                'obligatoire' => false,
                'ordre' => 6,
                'commentaire_guide' => 'Vérifier la tension d\'alimentation de l\'unité extérieure',
            ],
        ];

        foreach ($elementsMaintenanceClim as $elementData) {
            ElementModeleReleve::create(array_merge($elementData, [
                'modele_releve_id' => $modeleMaintenanceClim->id,
            ]));
        }

        // 2. Modèle de relevé pour diagnostic de panne
        $modeleDiagnosticPanne = ModeleReleve::create([
            'nom' => 'Diagnostic de Panne Climatisation',
            'description' => 'Modèle de relevé pour le diagnostic des pannes sur les systèmes de climatisation',
        ]);

        $elementsDiagnosticPanne = [
            [
                'type_mesure' => 'Température Évaporateur',
                'unite_attendue' => '°C',
                'emplacement_suggerer' => 'Évaporateur unité intérieure',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => true,
                'ordre' => 1,
                'commentaire_guide' => 'Mesurer la température de surface de l\'évaporateur',
            ],
            [
                'type_mesure' => 'Température Condenseur',
                'unite_attendue' => '°C',
                'emplacement_suggerer' => 'Condenseur unité extérieure',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => true,
                'ordre' => 2,
                'commentaire_guide' => 'Mesurer la température de surface du condenseur',
            ],
            [
                'type_mesure' => 'Surchauffe',
                'unite_attendue' => 'K',
                'emplacement_suggerer' => 'Sortie évaporateur',
                'valeur_min_attendue' => 5.0,
                'valeur_max_attendue' => 15.0,
                'obligatoire' => true,
                'ordre' => 3,
                'commentaire_guide' => 'Calculer la surchauffe : température gaz - température évaporation',
            ],
            [
                'type_mesure' => 'Sous-refroidissement',
                'unite_attendue' => 'K',
                'emplacement_suggerer' => 'Sortie condenseur',
                'valeur_min_attendue' => 3.0,
                'valeur_max_attendue' => 8.0,
                'obligatoire' => true,
                'ordre' => 4,
                'commentaire_guide' => 'Calculer le sous-refroidissement : température condensation - température liquide',
            ],
            [
                'type_mesure' => 'État Filtre',
                'unite_attendue' => '',
                'emplacement_suggerer' => 'Unité intérieure',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => false,
                'ordre' => 5,
                'commentaire_guide' => 'Indiquer l\'état du filtre : Propre / Sale / Obstrué',
            ],
        ];

        foreach ($elementsDiagnosticPanne as $elementData) {
            ElementModeleReleve::create(array_merge($elementData, [
                'modele_releve_id' => $modeleDiagnosticPanne->id,
            ]));
        }

        // 3. Modèle de relevé pour mise en service
        $modeleMiseEnService = ModeleReleve::create([
            'nom' => 'Mise en Service Climatisation',
            'description' => 'Modèle de relevé pour la mise en service et les tests de performance des nouveaux équipements',
        ]);

        $elementsMiseEnService = [
            [
                'type_mesure' => 'Test Étanchéité',
                'unite_attendue' => 'bar',
                'emplacement_suggerer' => 'Circuit frigorifique',
                'valeur_min_attendue' => 40.0,
                'valeur_max_attendue' => 45.0,
                'obligatoire' => true,
                'ordre' => 1,
                'commentaire_guide' => 'Effectuer un test d\'étanchéité à l\'azote avant mise en service',
            ],
            [
                'type_mesure' => 'Tirage au Vide',
                'unite_attendue' => 'mbar',
                'emplacement_suggerer' => 'Circuit frigorifique',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => 0.5,
                'obligatoire' => true,
                'ordre' => 2,
                'commentaire_guide' => 'Effectuer un tirage au vide complet du circuit avant chargement',
            ],
            [
                'type_mesure' => 'Débit d\'Air',
                'unite_attendue' => 'm³/h',
                'emplacement_suggerer' => 'Sortie unité intérieure',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => false,
                'ordre' => 3,
                'commentaire_guide' => 'Mesurer le débit d\'air de l\'unité intérieure',
            ],
            [
                'type_mesure' => 'Puissance Absorbée',
                'unite_attendue' => 'W',
                'emplacement_suggerer' => 'Compteur électrique',
                'valeur_min_attendue' => null,
                'valeur_max_attendue' => null,
                'obligatoire' => false,
                'ordre' => 4,
                'commentaire_guide' => 'Mesurer la puissance électrique absorbée en fonctionnement nominal',
            ],
        ];

        foreach ($elementsMiseEnService as $elementData) {
            ElementModeleReleve::create(array_merge($elementData, [
                'modele_releve_id' => $modeleMiseEnService->id,
            ]));
        }

        $this->command->info('✅ Modèles de relevé créés avec leurs éléments :');
        $this->command->info("  - {$modeleMaintenanceClim->nom} ({$modeleMaintenanceClim->elementsModeleReleve()->count()} éléments)");
        $this->command->info("  - {$modeleDiagnosticPanne->nom} ({$modeleDiagnosticPanne->elementsModeleReleve()->count()} éléments)");
        $this->command->info("  - {$modeleMiseEnService->nom} ({$modeleMiseEnService->elementsModeleReleve()->count()} éléments)");
    }
}
