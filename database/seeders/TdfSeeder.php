<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Equipement;
use App\Models\Modele;
use App\Models\Site;
use App\Models\TypeEquipement;
use App\Models\TypeGaz;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TdfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Insert Client TDF
        $client = Client::updateOrCreate(
            ['nom' => 'TDF'],
            [
                'nom' => 'TDF',
                'adresse' => 'Adresse TDF',
                'ville' => 'Saint-Denis',
                'code_postal' => '97400',
                'pays' => 'France',
                'telephone' => '0262000000',
                'email' => 'contact@tdf.fr',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('✅ Client TDF créé : '.$client->nom);

        // 2. Insert Gas Types
        // Les GWP sont des valeurs standards, ajustées si des données plus précises sont trouvées dans le PDF
        $gasTypes = [
            ['nom' => 'R410A', 'gwp' => 2088],
            ['nom' => 'R407C', 'gwp' => 1774],
            ['nom' => 'R32', 'gwp' => 675],
        ];

        $gasTypeIds = [];
        foreach ($gasTypes as $gasData) {
            $gasType = TypeGaz::updateOrCreate(
                ['nom' => $gasData['nom']],
                array_merge($gasData, ['created_at' => $now, 'updated_at' => $now])
            );
            $gasTypeIds[$gasData['nom']] = $gasType->id;
        }

        $this->command->info('✅ Types de gaz créés : '.count($gasTypes));

        // 3. Insert Equipment Type
        $equipType = TypeEquipement::updateOrCreate(
            ['nom' => 'Climatiseur'],
            [
                'nom' => 'Climatiseur',
                'description' => 'Équipement de type Split Système ou Monobloc',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('✅ Type d\'équipement créé : '.$equipType->nom);

        // 4. Insert Sites TDF
        // Les adresses sont extraites du PDF
        $sites = [
            ['nom' => 'Cascade', 'adresse' => 'Pointe des Cascades - Sainte Rose', 'ville' => 'Sainte Rose'],
            ['nom' => 'Barachois', 'adresse' => 'Saint Denis', 'ville' => 'Saint Denis'],
            ['nom' => 'Bélouve', 'adresse' => 'Salazie', 'ville' => 'Salazie'],
            ['nom' => 'St Bernard', 'adresse' => 'Saint Denis', 'ville' => 'Saint Denis'],
            ['nom' => 'Caserne', 'adresse' => 'Saint Pierre', 'ville' => 'Saint Pierre'],
            ['nom' => 'Le Chaudron', 'adresse' => 'Saint Denis', 'ville' => 'Saint Denis'],
            ['nom' => 'le Brûlé', 'adresse' => 'Saint Denis', 'ville' => 'Saint Denis'],
            ['nom' => 'Dos D\'âne', 'adresse' => 'La Possession', 'ville' => 'La Possession'],
            ['nom' => 'Découverte', 'adresse' => 'Salazie', 'ville' => 'Salazie'],
            ['nom' => 'Piton Défaut', 'adresse' => 'Piton Défaut', 'ville' => 'Piton Défaut'], // Ville non spécifiée, utilise le nom du site
            ['nom' => 'Entre Deux', 'adresse' => 'Saint Louis', 'ville' => 'Saint Louis'],
            ['nom' => 'Etablissement', 'adresse' => 'Petite île - Saint Joseph', 'ville' => 'Saint Joseph'],
            ['nom' => 'Fleurs jaune', 'adresse' => 'Cilaos', 'ville' => 'Cilaos'],
            ['nom' => 'Tour FT Saint Benoît', 'adresse' => 'Saint Benoît', 'ville' => 'Saint Benoît'],
            ['nom' => 'Tour FT Saint Pierre', 'adresse' => 'Saint Pierre', 'ville' => 'Saint Pierre'],
            ['nom' => 'Plaine des Grègues', 'adresse' => 'Saint Joseph', 'ville' => 'Saint Joseph'],
            ['nom' => 'Piton Hyacinthe', 'adresse' => 'Saint Pierre', 'ville' => 'Saint Pierre'],
            ['nom' => 'Manapany', 'adresse' => 'Saint Joseph', 'ville' => 'Saint Joseph'],
            ['nom' => 'Matouta', 'adresse' => 'Saint Joseph', 'ville' => 'Saint Joseph'],
            ['nom' => 'La Montagne', 'adresse' => 'Saint Denis', 'ville' => 'Saint Denis'],
            ['nom' => 'Jean Petit les Bas', 'adresse' => 'Saint Joseph', 'ville' => 'Saint Joseph'],
            ['nom' => 'Le Plate', 'adresse' => 'Les Avirons - Étang Salé', 'ville' => 'Les Avirons'],
            ['nom' => 'Le Port', 'adresse' => 'Pointe des Galets', 'ville' => 'Le Port'],
            ['nom' => 'Les Réservoir', 'adresse' => 'Sainte Rose', 'ville' => 'Sainte Rose'],
            ['nom' => 'Salazie Escalier', 'adresse' => 'Petit trou d\'escalier', 'ville' => 'Salazie'],
            ['nom' => 'Etang Salé', 'adresse' => 'Gros Piton', 'ville' => 'Etang Salé'],
            ['nom' => 'la Saline', 'adresse' => 'Saint Leu', 'ville' => 'Saint Leu'],
            ['nom' => 'Pointe de Bel Air', 'adresse' => 'Ste Suzanne', 'ville' => 'Ste Suzanne'],
            ['nom' => 'Le Tapage', 'adresse' => 'Saint Louis', 'ville' => 'Saint Louis'],
            ['nom' => 'Tévelave', 'adresse' => 'Les Avirons', 'ville' => 'Les Avirons'],
            ['nom' => 'Piton Textor', 'adresse' => 'Le Tampon', 'ville' => 'Le Tampon'],
            ['nom' => 'Le Tremblet', 'adresse' => 'Saint Philippe', 'ville' => 'Saint Philippe'],
            ['nom' => 'Vincendo', 'adresse' => 'Saint Philippe', 'ville' => 'Saint Philippe'],
        ];

        $siteIds = [];
        foreach ($sites as $siteData) {
            $site = Site::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'nom' => $siteData['nom'],
                ],
                [
                    'client_id' => $client->id,
                    'nom' => $siteData['nom'],
                    'adresse' => $siteData['adresse'],
                    'ville' => $siteData['ville'],
                    'code_postal' => '97400', // Code postal générique pour la Réunion
                    'pays' => 'France',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $siteIds[$siteData['nom']] = $site->id; // Stocke l'ID du site avec son nom comme clé
        }

        $this->command->info('✅ Sites TDF créés : '.count($sites));

        // 5. Insert Modèles d'équipements
        // Données extraites directement du PDF, en nettoyant et standardisant les références
        $modeles = [
            // AIRWELL
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-HND012-H11/AWSI-HKD012-N11', 'quantite_gaz_kg' => 0.95, 'type_gaz' => 'R410A'],
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YGF018-H11', 'quantite_gaz_kg' => 1.5, 'type_gaz' => 'R410A'],
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YGF012-H11', 'quantite_gaz_kg' => 1.0, 'type_gaz' => 'R410A'],
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YND018-N11', 'quantite_gaz_kg' => 1.48, 'type_gaz' => 'R410A'],
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YKD024-H11', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'],
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YKD009-H11', 'quantite_gaz_kg' => 0.8, 'type_gaz' => 'R410A'], // Ajusté d'après TDF-PORT-SPL-07
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-HKD024-H11', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // Ajusté d'après TDF-HYAC-SPL-03
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YLD024-H11', 'quantite_gaz_kg' => 2.2, 'type_gaz' => 'R410A'], // D'après TDF-FLEU-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YDDE09-H11', 'quantite_gaz_kg' => 0.8, 'type_gaz' => 'R410A'], // D'après TDF-FLEU-SPL-02
            ['marque' => 'AIRWELL', 'reference' => 'UE:AWAU-YKD012-H11/UI:AWSI-HKD012-N11', 'quantite_gaz_kg' => 0.95, 'type_gaz' => 'R410A'], // D'après TDF-FT-SP-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'AWASI-YDDE024-H11', 'quantite_gaz_kg' => 1.8, 'type_gaz' => 'R410A'], // D'après TDF-MANA-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'UE: AWAU-YG024-H11/UI: AWSI HHF024-N11', 'quantite_gaz_kg' => 1.45, 'type_gaz' => 'R410A'], // D'après TDF-MANA-SPL-03
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-HDK024-H11', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // D'après TDF-MONT-SPL-05
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YMD024-H11', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // D'après TDF-PLAT-SPL-02
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YND09-H11', 'quantite_gaz_kg' => 0.61, 'type_gaz' => 'R410A'], // D'après TDF-RESE-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'UE: AWAU-YKD024-H11/UI: AWSI-HKD024N11', 'quantite_gaz_kg' => 2.0, 'type_gaz' => 'R410A'], // D'après TDF-SUZ-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'UE: AWAU-YGF012-H11/UI: AWSI-HHF012-N11', 'quantite_gaz_kg' => 0.84, 'type_gaz' => 'R410A'], // D'après TDF-TEVE-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YGF024-H11', 'quantite_gaz_kg' => 1.45, 'type_gaz' => 'R410A'], // D'après TDF-TEXT-SPL-01
            ['marque' => 'AIRWELL', 'reference' => 'UE: AWAU-YKD024-H11', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // D'après TDF-TEXT-SPL-02
            ['marque' => 'AIRWELL', 'reference' => 'AWAU-YND012-H11', 'quantite_gaz_kg' => 1.1, 'type_gaz' => 'R410A'], // D'après TDF-TREM-SPL-02
            ['marque' => 'AIRWELL', 'reference' => 'UE: AWAU-YDDE018-H11/UI: AWSI-HDDE018-N11', 'quantite_gaz_kg' => 1.3, 'type_gaz' => 'R410A'], // D'après TDF-VINC-SPL-01

            // CARRIER
            ['marque' => 'CARRIER', 'reference' => '42QHC024DS/38QHC024DS', 'quantite_gaz_kg' => 2.0, 'type_gaz' => 'R410A'],
            ['marque' => 'CARRIER', 'reference' => '42QHC38QHC T9/38QHC009DS', 'quantite_gaz_kg' => 0.67, 'type_gaz' => 'R410A'],
            ['marque' => 'CARRIER', 'reference' => 'UE: 42QHC012DS / UI: 38QHC012DS', 'quantite_gaz_kg' => 0.68, 'type_gaz' => 'R410A'], // D'après TDF-DEFA-SPL-02
            ['marque' => 'CARRIER', 'reference' => 'UI: 42QHC018D8S/UE: 38QHC018D8S', 'quantite_gaz_kg' => 1.25, 'type_gaz' => 'R32'], // D'après TDF-DEUX-SPLSPL--01 01
            ['marque' => 'CARRIER', 'reference' => 'UI: 42QHC018DS/UE: 38QHC018DS', 'quantite_gaz_kg' => 1.65, 'type_gaz' => 'R410A'], // D'après TDF-GREG-SPL-01
            ['marque' => 'CARRIER', 'reference' => 'UI: 42QHC024D85/UE: 38QHC024D85', 'quantite_gaz_kg' => 1.6, 'type_gaz' => 'R32'], // D'après TDF-MONT-SPL-01
            ['marque' => 'CARRIER', 'reference' => 'UI: 42QHG012D8S/UE: 38QHG012D8S', 'quantite_gaz_kg' => 0.65, 'type_gaz' => 'R32'], // D'après TDF-PETI-SPL-02
            ['marque' => 'CARRIER', 'reference' => '42QZL024DS-1/38QUS024DS-1', 'quantite_gaz_kg' => 2.05, 'type_gaz' => 'R410A'], // D'après TDF-SALE-SPL-02 et TDF-SALI-SPL-04
            ['marque' => 'CARRIER', 'reference' => 'UI: 42QZL030DS-1/UE: 38QUS030DS-1', 'quantite_gaz_kg' => 2.8, 'type_gaz' => 'R410A'], // D'après TDF-SALI-SPL-03

            // WESTPOINT
            ['marque' => 'WESTPOINT', 'reference' => 'WFM-18LH', 'quantite_gaz_kg' => 1.4, 'type_gaz' => 'R410A'],
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-119.LE', 'quantite_gaz_kg' => 1.04, 'type_gaz' => 'R410A'], // Ajusté d'après PDF, était R407C
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-12.K', 'quantite_gaz_kg' => 0.9, 'type_gaz' => 'R407C'],
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-24.K', 'quantite_gaz_kg' => 2.0, 'type_gaz' => 'R407C'],
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-189.LHE', 'quantite_gaz_kg' => 1.5, 'type_gaz' => 'R410A'],
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-249.LE', 'quantite_gaz_kg' => 1.8, 'type_gaz' => 'R410A'],
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-2410.LHE', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // D'après TDF-FT-BEN-SPL-02
            ['marque' => 'WESTPOINT', 'reference' => 'WFM36L-3', 'quantite_gaz_kg' => 3.0, 'type_gaz' => 'R410A'], // D'après TDFFT--BEN-SPL-03 et TDF-HYAC-SPL-02
            ['marque' => 'WESTPOINT', 'reference' => 'WFM-60L', 'quantite_gaz_kg' => 3.9, 'type_gaz' => 'R410A'], // D'après TDF-HYAC-SPL-04
            ['marque' => 'WESTPOINT', 'reference' => 'WFM 24L', 'quantite_gaz_kg' => 2.6, 'type_gaz' => 'R410A'], // D'après TDF-MANA-SPL-02
            ['marque' => 'WESTPOINT', 'reference' => 'WIM-122116.LHE', 'quantite_gaz_kg' => 0.84, 'type_gaz' => 'R410A'], // D'après TDF-MATO-SPL-01
            ['marque' => 'WESTPOINT', 'reference' => 'WFM-36.13', 'quantite_gaz_kg' => 3.0, 'type_gaz' => 'R410A'], // D'après TDF-MONT-SPL-03
            ['marque' => 'WESTPOINT', 'reference' => 'WIM-12218.ΜΗ', 'quantite_gaz_kg' => 0.5, 'type_gaz' => 'R32'], // D'après TDF-PETI-SPL-01
            ['marque' => 'WESTPOINT', 'reference' => 'WFM36.L.3', 'quantite_gaz_kg' => 3.0, 'type_gaz' => 'R410A'], // D'après TDF-PLAT-SPL-03
            ['marque' => 'WESTPOINT', 'reference' => 'WFIM3613LHE', 'quantite_gaz_kg' => 2.75, 'type_gaz' => 'R410A'], // D'après TDF-PORT-SPL-01
            ['marque' => 'WESTPOINT', 'reference' => 'CSZ O13613HB13', 'quantite_gaz_kg' => 3.5, 'type_gaz' => 'R410A'], // D'après TDF-PORT-SPL-05
            ['marque' => 'WESTPOINT', 'reference' => 'UI: WFM-24.L/UE: WFM-24.L', 'quantite_gaz_kg' => 2.6, 'type_gaz' => 'R410A'], // D'après TDF-SALI-SPL-02
            ['marque' => 'WESTPOINT', 'reference' => 'WFM-48L', 'quantite_gaz_kg' => 3.7, 'type_gaz' => 'R410A'], // D'après TDF-TEXT-SPL-03
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-129-LE', 'quantite_gaz_kg' => 1.04, 'type_gaz' => 'R410A'], // D'après TDF-TEXT-SPL-05 et TDF-TEXT-SPL-06
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-1210-LHE', 'quantite_gaz_kg' => 1.25, 'type_gaz' => 'R410A'], // D'après TDF-TREM-SPL-01
            ['marque' => 'WESTPOINT', 'reference' => 'WSZ-189-LHE', 'quantite_gaz_kg' => 1.5, 'type_gaz' => 'R410A'], // D'après TDF-VINC-SPL-02

            // MDV
            ['marque' => 'MDV', 'reference' => 'MSC-09CRNI', 'quantite_gaz_kg' => 0.91, 'type_gaz' => 'R410A'],
            ['marque' => 'MDV', 'reference' => 'MSC-09CRN1', 'quantite_gaz_kg' => 0.91, 'type_gaz' => 'R410A'], // D'après TDF-CHA-SPL-06 et TDF-CHA-SPL-11
            ['marque' => 'MDV', 'reference' => 'MSC-09CRN2', 'quantite_gaz_kg' => 0.91, 'type_gaz' => 'R410A'], // D'après TDF-CHA-SPL-09
            ['marque' => 'MDV', 'reference' => 'MSC-09CRNT', 'quantite_gaz_kg' => 0.91, 'type_gaz' => 'R410A'], // D'après TDF-FLEU-SPL-03

            // ZENITH AIR
            ['marque' => 'ZENITH AIR', 'reference' => 'XSER18A', 'quantite_gaz_kg' => 1.85, 'type_gaz' => 'R410A'], // D'après TDF-CHA-SPL-02
            ['marque' => 'ZENITH AIR', 'reference' => 'XUSF18B', 'quantite_gaz_kg' => 2.0, 'type_gaz' => 'R410A'], // D'après TDF-BRUL-SPL-01
            ['marque' => 'ZENITH AIR', 'reference' => 'JARR36A', 'quantite_gaz_kg' => 3.2, 'type_gaz' => 'R410A'], // D'après TDF-FT-BEN-SPL-01
            ['marque' => 'ZENITH AIR', 'reference' => 'ISER 18A', 'quantite_gaz_kg' => 1.85, 'type_gaz' => 'R410A'], // D'après TDF-PLAT-SPL-01
            ['marque' => 'ZENITH AIR', 'reference' => 'XUSR36A', 'quantite_gaz_kg' => 3.0, 'type_gaz' => 'R410A'], // D'après TDF-PORT-SPL-04
            ['marque' => 'ZENITH AIR', 'reference' => 'XSLR18A', 'quantite_gaz_kg' => 1.15, 'type_gaz' => 'R410A'], // D'après TDF-SUZ-SPL-02

            // GALAXIE
            ['marque' => 'GALAXIE', 'reference' => 'AS12CINAIL', 'quantite_gaz_kg' => 0.75, 'type_gaz' => 'R407C'],
            ['marque' => 'GALAXIE', 'reference' => 'AS12CIDAL', 'quantite_gaz_kg' => 0.82, 'type_gaz' => 'R410A'],
            ['marque' => 'GALAXIE', 'reference' => 'AS09C1DA/C', 'quantite_gaz_kg' => 0.61, 'type_gaz' => 'R410A'], // D'après TDF-PORT-SPL-08
            ['marque' => 'GALAXIE', 'reference' => 'AS09CDA/C', 'quantite_gaz_kg' => 0.61, 'type_gaz' => 'R410A'], // D'après TDF-RESE-SPL-02
            ['marque' => 'GALAXIE', 'reference' => 'AS12C1DAB/', 'quantite_gaz_kg' => 0.85, 'type_gaz' => 'R410A'], // D'après TDF-VINC-SPL-03

            // MIDEA
            ['marque' => 'MIDEA', 'reference' => 'MOBA03-09HFN1-QRDOQGW', 'quantite_gaz_kg' => 0.8, 'type_gaz' => 'R410A'], // D'après TDF-CHA-SPL-17
            ['marque' => 'MIDEA', 'reference' => 'MOBO2-18HFN1-QRDOGW/MSMACU-18HRFN1-QRD0GW', 'quantite_gaz_kg' => 1.48, 'type_gaz' => 'R410A'], // D'après TDF-CHA-SPL-18
            ['marque' => 'MIDEA', 'reference' => 'UI: MUE-18HFRN1-QRDO/UE: MOB30U-18HFN1-QRDO', 'quantite_gaz_kg' => 1.78, 'type_gaz' => 'R410A'], // D'après TDF-HYAC-SPL-01
            ['marque' => 'MIDEA', 'reference' => 'MUE-18HRFN1-QRDO/MOB30U-18HFN1-QRDO', 'quantite_gaz_kg' => 1.48, 'type_gaz' => 'R410A'], // D'après TDF-PORT-SPL-03
            ['marque' => 'MIDEA', 'reference' => 'UI: MUE-24 HFRN1-QRDO/UE: MOCA3OU-24HFN1-QRDO', 'quantite_gaz_kg' => 1.95, 'type_gaz' => 'R410A'], // D'après TDF-TEXT-SPL-04

            // DAIKIN
            ['marque' => 'DAIKIN', 'reference' => 'RXN35CVMB7', 'quantite_gaz_kg' => 1.01, 'type_gaz' => 'R410A'], // D'après TDF-DANE-SPL-01
            ['marque' => 'DAIKIN', 'reference' => 'RKS60BVMB9', 'quantite_gaz_kg' => 1.7, 'type_gaz' => 'R410A'], // D'après TDF-MONT-SPL-04
            ['marque' => 'DAIKIN', 'reference' => 'RS60BVMB', 'quantite_gaz_kg' => 1.7, 'type_gaz' => 'R410A'], // D'après TDF-SALI-SPL-01

            // GREE
            ['marque' => 'GREE', 'reference' => 'GWC12C-A11', 'quantite_gaz_kg' => 0.95, 'type_gaz' => 'R410A'], // D'après TDF-DEFA-SPL-01

            // TOP COOL
            ['marque' => 'TOP COOL', 'reference' => 'TOP 36D', 'quantite_gaz_kg' => 3.0, 'type_gaz' => 'R407C'], // D'après TDF-MONT-SPL-06
        ];

        $modeleIds = [];
        foreach ($modeles as $modeleData) {
            $modele = Modele::updateOrCreate(
                [
                    'marque' => $modeleData['marque'],
                    'reference_constructeur' => $modeleData['reference'],
                    'type_equipement_id' => $equipType->id,
                ],
                [
                    'type_equipement_id' => $equipType->id,
                    'marque' => $modeleData['marque'],
                    'nom' => $modeleData['marque'].' '.$modeleData['reference'],
                    'reference_constructeur' => $modeleData['reference'],
                    'quantite_gaz_kg' => $modeleData['quantite_gaz_kg'],
                    'type_gaz_id' => $gasTypeIds[$modeleData['type_gaz']],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $modeleIds[$modeleData['marque'].' '.$modeleData['reference']] = $modele->id;
        }

        $this->command->info('✅ Modèles d\'équipements créés : '.count($modeles));

        // 6. Insert Équipements
        // Données extraites du PDF
        $equipementsData = [
            // Cascade
            ['site' => 'Cascade', 'nom' => 'Split Système - Piton l\'Anse des Cascades', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-HND012-H11/AWSI-HKD012-N11', 'numero_serie' => 'TDF-CASC-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Cascade', 'nom' => 'Split Système - Piton l\'Anse des Cascades', 'marque' => 'CARRIER', 'modele_ref' => '42QHC024DS/38QHC024DS', 'numero_serie' => 'TDF-CASC-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Cascade', 'nom' => 'Split Système - Piton l\'Anse des Cascades - Local 2', 'marque' => 'CARRIER', 'modele_ref' => '42QHC38QHC T9/38QHC009DS', 'numero_serie' => 'TDF-CASC-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Cascade', 'nom' => 'Split Système - Piton l\'Anse des Cascades - Local 2', 'marque' => 'CARRIER', 'modele_ref' => '42QHC38QHC T9/38 QHC009DS', 'numero_serie' => 'TDF-CASC-SPL-04', 'etat' => 'Normal'],

            // Barachois
            ['site' => 'Barachois', 'nom' => 'Split Système - LE Barachois', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YGF018-H11', 'numero_serie' => 'TDF-BARA-SPL-01', 'etat' => 'Normal'],

            // Bélouve
            ['site' => 'Bélouve', 'nom' => 'Split Système - Bélouve', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM-18LH', 'numero_serie' => 'TDF-BELOUV-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Bélouve', 'nom' => 'Split Système - Bélouve', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-BELOUV-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Bélouve', 'nom' => 'Split Système - Bélouve', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-BELOUV-SPL-03', 'etat' => 'Normal'],

            // St Bernard
            ['site' => 'St Bernard', 'nom' => 'Split Système - Chemin du cimetière', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-119.LE', 'numero_serie' => 'TDF-BERN-SPL-01', 'etat' => 'Normal'],

            // Le Chaudron
            ['site' => 'Le Chaudron', 'nom' => 'Labo 1', 'marque' => 'ZENITH AIR', 'modele_ref' => 'XSER18A', 'numero_serie' => 'Labo 1 TDF-CHA-SPL-02', 'etat' => 'Normal'], // Numéro de série ajusté
            ['site' => 'Le Chaudron', 'nom' => 'Split Système - Chaudron / Labo', 'marque' => 'ZENITH AIR', 'modele_ref' => 'XSER18A', 'numero_serie' => 'TDF-CHA-SPL-03', 'etat' => 'Normal'], // Modèle R407C non trouvé, utilise R410A
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron / Responsable maintenance', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-12.K', 'numero_serie' => 'TDF-CHA-SPL-04', 'etat' => 'Fin de cycle'],
            ['site' => 'Le Chaudron', 'nom' => 'Salle de réunion', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-24.K', 'numero_serie' => 'TDF-CHA-SPL-05', 'etat' => 'Fin de cycle'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron/Secrétariat', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRN1', 'numero_serie' => 'TDF-CHA-SPL-06', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron / Délégué Territorial', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YGF012-H11', 'numero_serie' => 'TDF-CHA-SPL-07', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron / Ordonnanceur', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-CHA-SPL08-', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron/Code', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRN2', 'numero_serie' => 'TDF-CHA-SPL-09', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Salle de pose / Réfectoire', 'marque' => 'GALAXIE', 'modele_ref' => 'AS12CINAIL', 'numero_serie' => 'TDF-CHA-SPL-10', 'etat' => 'Fin de cycle'],
            ['site' => 'Le Chaudron', 'nom' => 'Bureau Rode', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRN1', 'numero_serie' => 'TDF-CHA-SPL-11', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron / Bureau', 'marque' => 'GALAXIE', 'modele_ref' => 'AS12CIDAL', 'numero_serie' => 'TDF-CHA-SPL-12', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Stock', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-189.LHE', 'numero_serie' => 'TDF-CHA-SPL-13', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron - Local TMS', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YND018-N11', 'numero_serie' => 'TDF-CHA-SPL-14', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Local TMS', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-249.LE', 'numero_serie' => 'TDF-CHA-SPL-15', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Salle TMS', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD024-H11', 'numero_serie' => 'TDF-CHA-SPL-16', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron Petit Bureau', 'marque' => 'MIDEA', 'modele_ref' => 'MOBA03-09HFN1-QRDOQGW', 'numero_serie' => 'TDF-CHA-SPL-17', 'etat' => 'Normal'],
            ['site' => 'Le Chaudron', 'nom' => 'Chaudron / Local Stock', 'marque' => 'MIDEA', 'modele_ref' => 'MOBO2-18HFN1-QRDOGW/MSMACU-18HRFN1-QRD0GW', 'numero_serie' => 'TDF-CHA-SPL-18', 'etat' => 'Normal'],

            // Le Brûlé
            ['site' => 'le Brûlé', 'nom' => 'Split Système - 894b chemin du général de Gaulle', 'marque' => 'ZENITH AIR', 'modele_ref' => 'XUSF18B', 'numero_serie' => 'TDF-BRUL-SPL-01', 'etat' => 'Normal'],

            // Dos D'âne
            ['site' => 'Dos D\'âne', 'nom' => 'Split Système - dos d\'Ane', 'marque' => 'DAIKIN', 'modele_ref' => 'RXN35CVMB7', 'numero_serie' => 'TDF-DANE-SPL-01', 'etat' => 'Normal'],

            // Découverte
            ['site' => 'Découverte', 'nom' => 'Split Système - La Plaine des fougères - La découverte', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD012-H11', 'numero_serie' => 'TDF-DECO-SPL-01', 'etat' => 'Normal'],

            // Piton Défaut
            ['site' => 'Piton Défaut', 'nom' => 'Split Système - Piton Défaut', 'marque' => 'GREE', 'modele_ref' => 'GWC12C-A11', 'numero_serie' => 'TDF-DEFA-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Piton Défaut', 'nom' => 'Split Système - Piton Défaut', 'marque' => 'CARRIER', 'modele_ref' => 'UE: 42QHC012DS / UI: 38QHC012DS', 'numero_serie' => 'TDF-DEFA-SPL-02', 'etat' => 'Normal'],

            // Entre Deux
            ['site' => 'Entre Deux', 'nom' => 'Split Système - Entre Deux', 'marque' => 'CARRIER', 'modele_ref' => 'UI: 42QHC018D8S/UE: 38QHC018D8S', 'numero_serie' => 'TDF-DEUX-SPLSPL--01 01', 'etat' => 'Normal'],

            // Etablissement
            ['site' => 'Etablissement', 'nom' => 'Split Système - Etablissement', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD012-H11', 'numero_serie' => 'TDF-ETAB-SPL-01', 'etat' => 'Normal'],

            // Fleurs jaune
            ['site' => 'Fleurs jaune', 'nom' => 'Split Système - Fleurs Jaunes', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YLD024-H11', 'numero_serie' => 'TDF-FLEU-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Fleurs jaune', 'nom' => 'Split Système - Fleurs Jaunes', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YDDE09-H11', 'numero_serie' => 'TDF-FLEU-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Fleurs jaune', 'nom' => 'Split Système - Fleurs Jaunes', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNT', 'numero_serie' => 'TDF-FLEU-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Fleurs jaune', 'nom' => 'Split Système - Fleurs Jaunes', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-FLEU-SPL-04', 'etat' => 'Normal'],

            // Tour FT Saint Benoît
            ['site' => 'Tour FT Saint Benoît', 'nom' => 'Split Système - Tour FT', 'marque' => 'ZENITH AIR', 'modele_ref' => 'JARR36A', 'numero_serie' => 'TDF-FT-BEN-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Tour FT Saint Benoît', 'nom' => 'Split Système - Tour FT', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-2410.LHE', 'numero_serie' => 'TDF-FT-BEN-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Tour FT Saint Benoît', 'nom' => 'Split Système - Tour FT', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM36L-3', 'numero_serie' => 'TDFFT--BEN-SPL-03', 'etat' => 'Normal'],

            // Tour FT Saint Pierre
            ['site' => 'Tour FT Saint Pierre', 'nom' => 'Split Système - Tour FT St Pierre', 'marque' => 'AIRWELL', 'modele_ref' => 'UE:AWAU-YKD012-H11/UI:AWSI-HKD012-N11', 'numero_serie' => 'TDF-FT-SP-SPL-01', 'etat' => 'Normal'],
            // ['site' => 'Tour FT Saint Pierre', 'nom' => 'Split Système - Tour FT St Pierre', 'marque' => 'WESTPOINT', 'modele_ref' => 'MODELE_INCONNU_WESTPOINT_R407C', 'numero_serie' => 'TDF-FT-SP-SPL-02', 'etat' => 'Normal'], // Modèle non spécifié, ignoré ou à ajouter manuellement si nécessaire

            // Plaine des Grègues
            ['site' => 'Plaine des Grègues', 'nom' => 'Split Système - Plaine des grègues', 'marque' => 'CARRIER', 'modele_ref' => 'UI: 42QHC018DS/UE: 38QHC018DS', 'numero_serie' => 'TDF-GREG-SPL-01', 'etat' => 'Normal'],

            // Piton Hyacinthe
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton Hyacinthe', 'marque' => 'MIDEA', 'modele_ref' => 'UI: MUE-18HFRN1-QRDO/UE: MOB30U-18HFN1-QRDO', 'numero_serie' => 'TDF-HYAC-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton Hyacinthe', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM36L-3', 'numero_serie' => 'TDF-HYAC-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton Hyacinthe', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-HKD024-H11', 'numero_serie' => 'TDF-HYAC-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton HYACINTHE', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM-60L', 'numero_serie' => 'TDF-HYAC-SPL-04', 'etat' => 'Normal'],
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton Hyacinthe', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-HYAC-SPL-05', 'etat' => 'Normal'],
            ['site' => 'Piton Hyacinthe', 'nom' => 'Split Système - Piton Hyacinthe', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-HYAC-SPL-06', 'etat' => 'Normal'],

            // Manapany
            ['site' => 'Manapany', 'nom' => 'Split Système - Manapany les hauts- chemin Léopold Lebon', 'marque' => 'AIRWELL', 'modele_ref' => 'AWASI-YDDE024-H11', 'numero_serie' => 'TDF-MANA-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Manapany', 'nom' => 'Split Système - Manapany les hauts- chemin Léopold Lebon', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM 24L', 'numero_serie' => 'TDF-MANA-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Manapany', 'nom' => 'Split Système - Manapany les hauts- chemin Léopold Lebon', 'marque' => 'AIRWELL', 'modele_ref' => 'UE: AWAU-YG024-H11/UI: AWSI HHF024-N11', 'numero_serie' => 'TDF-MANA-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Manapany', 'nom' => 'Split Système - Manapany les hauts- chemin Léopold Lebon', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-MANA-SPL-04', 'etat' => 'Normal'],
            ['site' => 'Manapany', 'nom' => 'Split Système - Manapany les hauts-chemin Léopold Lebon', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-MANA-SPL-05', 'etat' => 'Normal'],

            // Matouta
            ['site' => 'Matouta', 'nom' => 'Split Système - Matouta', 'marque' => 'WESTPOINT', 'modele_ref' => 'WIM-122116.LHE', 'numero_serie' => 'TDF-MATO-SPL-01', 'etat' => 'Normal'],

            // La Montagne
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'CARRIER', 'modele_ref' => 'UI: 42QHC024D85/UE: 38QHC024D85', 'numero_serie' => 'TDF-MONT-SPL-01', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD024-H11', 'numero_serie' => 'TDF-MONT-SPL-02', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM-36.13', 'numero_serie' => 'TDF-MONT-SPL-03', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'DAIKIN', 'modele_ref' => 'RKS60BVMB9', 'numero_serie' => 'TDF-MONT-SPL-04', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-HDK024-H11', 'numero_serie' => 'TDF-MONT-SPL-05', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'TOP COOL', 'modele_ref' => 'TOP 36D', 'numero_serie' => 'TDF-MONT-SPL-06', 'etat' => 'Fin de cycle'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRN1', 'numero_serie' => 'TDF-MONT-SPL-07', 'etat' => 'Normal'],
            ['site' => 'La Montagne', 'nom' => 'Split Système - Chemin de Notre dame', 'marque' => 'MDV', 'modele_ref' => 'MSC-09CRNI', 'numero_serie' => 'TDF-MONT-SPL-08', 'etat' => 'Normal'],

            // Jean Petit les Bas
            ['site' => 'Jean Petit les Bas', 'nom' => 'Split Système - Jean Petit', 'marque' => 'WESTPOINT', 'modele_ref' => 'WIM-12218.ΜΗ', 'numero_serie' => 'TDF-PETI-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Jean Petit les Bas', 'nom' => 'Split Système - Jean Petit', 'marque' => 'CARRIER', 'modele_ref' => 'UI: 42QHG012D8S/UE: 38QHG012D8S', 'numero_serie' => 'TDF-PETI-SPL-02', 'etat' => 'Normal'],

            // Le Plate
            ['site' => 'Le Plate', 'nom' => 'Split Système - Chemin des quatre sous', 'marque' => 'ZENITH AIR', 'modele_ref' => 'ISER 18A', 'numero_serie' => 'TDF-PLAT-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Le Plate', 'nom' => 'Split Système - Chemin des quatre sous', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YMD024-H11', 'numero_serie' => 'TDF-PLAT-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Le Plate', 'nom' => 'Split Système - Chemin des quatre sous', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM36.L.3', 'numero_serie' => 'TDF-PLAT-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Le Plate', 'nom' => 'Split Système - Chemin des quatre sous', 'marque' => 'INCONNU', 'modele_ref' => 'INCONNU', 'numero_serie' => 'TDF-PLAT-SPL-04', 'etat' => 'Obsolète'], // Modèle inconnu

            // Le Port
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFIM3613LHE', 'numero_serie' => 'TDF-PORT-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'MIDEA', 'modele_ref' => 'MUE-18HRFN1-QRDO/MOB30U-18HFN1-QRDO', 'numero_serie' => 'TDF-PORT-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'ZENITH AIR', 'modele_ref' => 'XUSR36A', 'numero_serie' => 'TDF-PORT-SPL-04', 'etat' => 'Normal'],
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'WESTPOINT', 'modele_ref' => 'CSZ O13613HB13', 'numero_serie' => 'TDF-PORT-SPL-05', 'etat' => 'Normal'],
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD009-H11', 'numero_serie' => 'TDF-PORT-SPL-07', 'etat' => 'Normal'],
            ['site' => 'Le Port', 'nom' => 'Split Système - Le Port', 'marque' => 'GALAXIE', 'modele_ref' => 'AS09C1DA/C', 'numero_serie' => 'TDF-PORT-SPL-08', 'etat' => 'Normal'],

            // Les Réservoir
            ['site' => 'Les Réservoir', 'nom' => 'Split Système - Les réservoirs', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YND09-H11', 'numero_serie' => 'TDF-RESE-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Les Réservoir', 'nom' => 'Split Système - Les réservoirs', 'marque' => 'GALAXIE', 'modele_ref' => 'AS09CDA/C', 'numero_serie' => 'TDF-RESE-SPL-02', 'etat' => 'Fin de cycle'],

            // Etang Salé
            ['site' => 'Etang Salé', 'nom' => 'Split Système - Gros Piton', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YLD024-H11', 'numero_serie' => 'TDF-SALE-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Etang Salé', 'nom' => 'Split Système - Gros Piton', 'marque' => 'CARRIER', 'modele_ref' => '42QZL024DS-1/38QUS024DS-1', 'numero_serie' => 'TDF-SALE-SPL-02', 'etat' => 'Normal'],

            // la Saline
            ['site' => 'la Saline', 'nom' => 'Split Système - La saline les hauts- CD9', 'marque' => 'DAIKIN', 'modele_ref' => 'RS60BVMB', 'numero_serie' => 'TDF-SALI-SPL-01', 'etat' => 'Fin de cycle'],
            ['site' => 'la Saline', 'nom' => 'Split Système - La saline les hauts- CD9', 'marque' => 'WESTPOINT', 'modele_ref' => 'UI: WFM-24.L/UE: WFM-24.L', 'numero_serie' => 'TDF-SALI-SPL-02', 'etat' => 'Normal'],
            ['site' => 'la Saline', 'nom' => 'Split Système - La saline les hauts- CD9', 'marque' => 'CARRIER', 'modele_ref' => 'UI: 42QZL030DS-1/UE: 38QUS030DS-1', 'numero_serie' => 'TDF-SALI-SPL-03', 'etat' => 'Normal'],
            ['site' => 'la Saline', 'nom' => 'Split Système - La saline les hauts- CD9', 'marque' => 'CARRIER', 'modele_ref' => '42QZL024DS-1/38QUS024DS-1', 'numero_serie' => 'TDF-SALI-SPL-04', 'etat' => 'Normal'],

            // Pointe de Bel Air
            ['site' => 'Pointe de Bel Air', 'nom' => 'Split Système - Rampe du Bel Air - Allée des Orchidées', 'marque' => 'AIRWELL', 'modele_ref' => 'UE: AWAU-YKD024-H11/UI: AWSI-HKD024N11', 'numero_serie' => 'TDF-SUZ-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Pointe de Bel Air', 'nom' => 'Split Système - Rampe du Bel Air - Allée des Orchidées', 'marque' => 'ZENITH AIR', 'modele_ref' => 'XSLR18A', 'numero_serie' => 'TDF-SUZ-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Pointe de Bel Air', 'nom' => 'Split Système - Rampe du Bel Air - Allée des Orchidées', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD024-H11', 'numero_serie' => 'TDF-SUZ-SPL-03', 'etat' => 'Normal'],

            // Le Tapage
            ['site' => 'Le Tapage', 'nom' => 'Split Système - Les Casernes Tapage', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YKD012-H11', 'numero_serie' => 'TDF-TAPA-SPL-01', 'etat' => 'Normal'],

            // Tévelave
            ['site' => 'Tévelave', 'nom' => 'Split Système - Tévelave', 'marque' => 'AIRWELL', 'modele_ref' => 'UE: AWAU-YGF012-H11/UI: AWSI-HHF012-N11', 'numero_serie' => 'TDF-TEVE-SPL-01', 'etat' => 'Normal'],

            // Piton Textor
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YGF024-H11', 'numero_serie' => 'TDF-TEXT-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'AIRWELL', 'modele_ref' => 'UE: AWAU-YKD024-H11', 'numero_serie' => 'TDF-TEXT-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'WESTPOINT', 'modele_ref' => 'WFM-48L', 'numero_serie' => 'TDF-TEXT-SPL-03', 'etat' => 'Normal'],
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'MIDEA', 'modele_ref' => 'UI: MUE-24 HFRN1-QRDO/UE: MOCA3OU-24HFN1-QRDO', 'numero_serie' => 'TDF-TEXT-SPL-04', 'etat' => 'Normal'],
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-129-LE', 'numero_serie' => 'TDF-TEXT-SPL-05', 'etat' => 'Normal'],
            ['site' => 'Piton Textor', 'nom' => 'Split Système - Piton Textor', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-129-LE', 'numero_serie' => 'TDF-TEXT-SPL-06', 'etat' => 'Normal'],

            // Le Tremblet
            ['site' => 'Le Tremblet', 'nom' => 'Split Système - Le Tremblet', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-1210-LHE', 'numero_serie' => 'TDF-TREM-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Le Tremblet', 'nom' => 'Split Système - Le Tremblet', 'marque' => 'AIRWELL', 'modele_ref' => 'AWAU-YND012-H11', 'numero_serie' => 'TDF-TREM-SPL-02', 'etat' => 'Normal'],

            // Vincendo
            ['site' => 'Vincendo', 'nom' => 'Split Système - Vincendo', 'marque' => 'AIRWELL', 'modele_ref' => 'UE: AWAU-YDDE018-H11/UI: AWSI-HDDE018-N11', 'numero_serie' => 'TDF-VINC-SPL-01', 'etat' => 'Normal'],
            ['site' => 'Vincendo', 'nom' => 'Split Système - Vincendo', 'marque' => 'WESTPOINT', 'modele_ref' => 'WSZ-189-LHE', 'numero_serie' => 'TDF-VINC-SPL-02', 'etat' => 'Normal'],
            ['site' => 'Vincendo', 'nom' => 'Split Système - Vincendo', 'marque' => 'GALAXIE', 'modele_ref' => 'AS12C1DAB/', 'numero_serie' => 'TDF-VINC-SPL-03', 'etat' => 'Normal'],
        ];

        $equipementCounter = 0;
        foreach ($equipementsData as $equipData) {
            $modeleKey = $equipData['marque'].' '.$equipData['modele_ref'];
            $modeleId = $modeleIds[$modeleKey] ?? null;

            if ($modeleId) {
                // Date d'installation aléatoire (entre 6 mois et 3 ans)
                $dateInstallation = $now->copy()->subDays(rand(180, 1095));

                Equipement::updateOrCreate(
                    ['numero_serie' => $equipData['numero_serie']],
                    [
                        'modele_id' => $modeleId,
                        'site_id' => $siteIds[$equipData['site']],
                        'nom' => $equipData['nom'],
                        'numero_serie' => $equipData['numero_serie'],
                        'date_installation' => $dateInstallation,
                        'etat' => $equipData['etat'],
                        'localisation_precise' => 'Non spécifié', // Le PDF ne donne pas de localisation précise au-delà de la description
                        'description' => $equipData['nom'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                $equipementCounter++;
            } else {
                $this->command->warn("⚠️ Modèle non trouvé pour l'équipement : ".$modeleKey);
            }
        }

        $this->command->info('✅ Équipements créés : '.$equipementCounter);

        // Résumé final
        $this->command->info('');
        $this->command->info('🎉 === SEEDING TDF COMPLET TERMINÉ ===');
        $this->command->info("📊 Client créé : {$client->nom}");
        $this->command->info('🏢 Sites créés : '.count($sites));
        $this->command->info('🔧 Modèles créés : '.count($modeles));
        $this->command->info('⚙️ Équipements créés : '.$equipementCounter);
        $this->command->info('🌡️ Types de gaz créés : '.count($gasTypes));
        $this->command->info('');
    }
}
