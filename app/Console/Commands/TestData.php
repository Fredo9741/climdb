<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Equipement;
use App\Models\Facture;
use App\Models\Intervention;
use App\Models\Panne;
use App\Models\Site;
use Illuminate\Console\Command;

final class TestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer des données de test et tester toutes les fonctionnalités';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Test des fonctionnalités avec données réelles');
        $this->line('');

        // Créer des données de test
        $this->createTestData();

        // Tester les fonctionnalités
        $this->testFunctionalities();

        $this->line('');
        $this->info('✅ Test complet terminé !');

        return 0;
    }

    private function createTestData(): void
    {
        $this->info('📦 Création de données de test...');

        // Créer un devis
        $client = Client::first();
        $site = Site::first();

        if ($client && $site) {
            $devis = Devis::firstOrCreate([
                'numero_devis' => 'DEV-2025-001',
            ], [
                'client_id' => $client->id,
                'site_id' => $site->id,
                'objet' => 'Maintenance préventive annuelle',
                'description' => 'Révision complète des systèmes de climatisation avec nettoyage des filtres et vérification des fluides frigorigènes.',
                'montant_ht' => 850.00,
                'taux_tva' => 20.0,
                'montant_ttc' => 1020.00,
                'date_emission' => now(),
                'date_validite' => now()->addMonth(),
                'statut' => 'en_attente',
                'conditions' => 'Paiement à 30 jours fin de mois par virement bancaire.',
            ]);

            $this->line("   ✅ Devis créé: {$devis->numero_devis}");
        }

        // Créer une facture si pas de devis
        if ($client) {
            $facture = Facture::firstOrCreate([
                'numero_facture' => 'FAC-2025-001',
            ], [
                'client_id' => $client->id,
                'devis_id' => $devis->id ?? null,
                'objet' => 'Maintenance effectuée le '.now()->format('d/m/Y'),
                'description' => 'Intervention de maintenance préventive réalisée selon le planning annuel.',
                'montant_ht' => 680.00,
                'taux_tva' => 20.0,
                'montant_ttc' => 816.00,
                'date_emission' => now(),
                'date_echeance' => now()->addDays(30),
                'statut' => 'emise',
                'conditions_paiement' => 'Paiement à 30 jours par virement bancaire',
            ]);

            $this->line("   ✅ Facture créée: {$facture->numero_facture}");
        }

        // Créer une intervention
        $equipement = Equipement::first();
        if ($equipement) {
            $intervention = Intervention::firstOrCreate([
                'equipement_id' => $equipement->id,
                'date_intervention' => now(),
            ], [
                'type_intervention' => 'Maintenance préventive',
                'description_travaux' => 'Nettoyage des filtres, vérification du niveau de fluide frigorigène, contrôle des connexions électriques.',
                'statut' => 'terminee',
                'duree_prevue' => 2.0,
                'duree_reelle' => 1.5,
                'cout_main_oeuvre' => 120.00,
                'cout_pieces' => 45.00,
                'observations' => 'Remplacement du filtre principal recommandé dans 6 mois.',
            ]);

            $this->line("   ✅ Intervention créée pour l'équipement: {$equipement->nom}");
        }

        $this->line('');
    }

    private function testFunctionalities(): void
    {
        $this->info('🔧 Test des fonctionnalités principales...');

        // Test Dashboard
        $this->testDashboard();

        // Test CRUD Clients
        $this->testClients();

        // Test CRUD Sites
        $this->testSites();

        // Test CRUD Équipements
        $this->testEquipements();

        // Test CRUD Pannes
        $this->testPannes();

        // Test CRUD Interventions
        $this->testInterventions();

        // Test CRUD Devis
        $this->testDevis();

        // Test CRUD Factures
        $this->testFactures();
    }

    private function testDashboard(): void
    {
        $this->line('   📊 Dashboard...');

        $stats = [
            'clients' => Client::count(),
            'sites' => Site::count(),
            'equipements' => Equipement::count(),
            'pannes_ouvertes' => Panne::where('statut', 'ouverte')->count(),
            'interventions_planifiees' => Intervention::where('statut', 'planifiee')->count(),
            'devis_en_attente' => Devis::where('statut', 'en_attente')->count(),
            'factures_impayees' => Facture::whereIn('statut', ['emise', 'envoyee', 'en_retard'])->count(),
        ];

        foreach ($stats as $key => $value) {
            $this->line("      - {$key}: {$value}");
        }
    }

    private function testClients(): void
    {
        $this->line('   🏢 Clients...');
        $clients = Client::all();
        $this->line("      - {$clients->count()} clients en base");

        foreach ($clients->take(3) as $client) {
            $sitesCount = $client->sites()->count();
            $clientName = $client->nom;
            $this->line("      - {$clientName} ({$sitesCount} sites)");
        }
    }

    private function testSites(): void
    {
        $this->line('   🏗️  Sites...');
        $sites = Site::with('client', 'equipements')->get();
        $this->line("      - {$sites->count()} sites en base");

        foreach ($sites->take(3) as $site) {
            $equipementsCount = $site->equipements()->count();
            $clientName = $site->client->nom;
            $this->line("      - {$site->nom} - {$clientName} ({$equipementsCount} équipements)");
        }
    }

    private function testEquipements(): void
    {
        $this->line('   🔧 Équipements...');
        $equipements = Equipement::with('site.client', 'modele.type_equipement')->get();
        $this->line("      - {$equipements->count()} équipements en base");

        foreach ($equipements->take(3) as $equipement) {
            $type = $equipement->modele->type_equipement->nom ?? 'N/A';
            $this->line("      - {$equipement->nom} ({$type}) - {$equipement->statut}");
        }
    }

    private function testPannes(): void
    {
        $this->line('   ⚠️  Pannes...');
        $pannes = Panne::with('equipement')->get();
        $this->line("      - {$pannes->count()} pannes en base");

        foreach ($pannes->take(3) as $panne) {
            $this->line("      - {$panne->description} ({$panne->statut}) - {$panne->equipement->nom}");
        }
    }

    private function testInterventions(): void
    {
        $this->line('   🔨 Interventions...');
        $interventions = Intervention::with('equipement')->get();
        $this->line("      - {$interventions->count()} interventions en base");

        foreach ($interventions->take(3) as $intervention) {
            $this->line("      - {$intervention->type_intervention} ({$intervention->statut}) - {$intervention->equipement->nom}");
        }
    }

    private function testDevis(): void
    {
        $this->line('   📋 Devis...');
        $devis = Devis::with('client')->get();
        $this->line("      - {$devis->count()} devis en base");

        foreach ($devis->take(3) as $dev) {
            $clientName = $dev->client->nom;
            $this->line("      - {$dev->numero_devis} - {$clientName} ({$dev->statut}) - {$dev->montant_ttc}€");
        }
    }

    private function testFactures(): void
    {
        $this->line('   🧾 Factures...');
        $factures = Facture::with('client')->get();
        $this->line("      - {$factures->count()} factures en base");

        foreach ($factures->take(3) as $facture) {
            $clientName = $facture->client->nom;
            $this->line("      - {$facture->numero_facture} - {$clientName} ({$facture->statut}) - {$facture->montant_ttc}€");
        }
    }
}
