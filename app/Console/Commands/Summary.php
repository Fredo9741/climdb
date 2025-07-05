<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Client;
use App\Models\Site;
use App\Models\Equipement;
use App\Models\Panne;
use App\Models\Intervention;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Console\Command;

final class Summary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Résumé complet de l\'application ClimDB';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🏢 APPLICATION CLIMDB - RÉSUMÉ COMPLET');
        $this->line('════════════════════════════════════════════');
        $this->line('');

        $this->showDatabaseStats();
        $this->showFeatures();
        $this->showTechnicalStack();
        $this->showAccessInfo();
        $this->showUsageGuide();
        
        $this->line('');
        $this->info('✅ Application ClimDB prête à l\'utilisation !');
        
        return 0;
    }

    private function showDatabaseStats(): void
    {
        $this->info('📊 STATISTIQUES DE LA BASE DE DONNÉES');
        $this->line('────────────────────────────────────────');
        
        $users = User::count();
        $clients = Client::count();
        $sites = Site::count();
        $equipements = Equipement::count();
        $pannes = Panne::count();
        $interventions = Intervention::count();
        $devis = Devis::count();
        $factures = Facture::count();
        
        $this->table(['Element', 'Nombre'], [
            ['👥 Utilisateurs', $users],
            ['🏢 Clients', $clients],
            ['🏗️ Sites', $sites],
            ['🔧 Équipements', $equipements],
            ['⚠️ Pannes', $pannes],
            ['🔨 Interventions', $interventions],
            ['📋 Devis', $devis],
            ['🧾 Factures', $factures],
        ]);
        
        $this->line('');
    }

    private function showFeatures(): void
    {
        $this->info('🚀 FONCTIONNALITÉS DISPONIBLES');
        $this->line('────────────────────────────────');
        
        $features = [
            '📊 Dashboard' => [
                '• Vue d\'ensemble des statistiques',
                '• Pannes urgentes à traiter',
                '• Actions rapides',
                '• Indicateurs de performance'
            ],
            '🏢 Gestion des Clients' => [
                '• CRUD complet (Créer, Lire, Modifier, Supprimer)',
                '• Informations entreprise et contact',
                '• Historique des interventions',
                '• Gestion des sites associés'
            ],
            '🏗️ Gestion des Sites' => [
                '• Localisation et informations détaillées',
                '• Association avec les clients',
                '• Gestion des équipements par site',
                '• Historique des interventions'
            ],
            '🔧 Gestion des Équipements' => [
                '• Inventaire complet des équipements',
                '• Suivi des statuts et emplacements',
                '• Historique de maintenance',
                '• Associations avec modèles et types'
            ],
            '⚠️ Gestion des Pannes' => [
                '• Déclaration et suivi des pannes',
                '• Priorisation par urgence',
                '• Assignation aux techniciens',
                '• Historique complet'
            ],
            '🔨 Gestion des Interventions' => [
                '• Planification des interventions',
                '• Suivi temps et coûts',
                '• Rapports d\'intervention',
                '• Gestion des pièces détachées'
            ],
            '📋 Gestion des Devis' => [
                '• Création et suivi des devis',
                '• Calculs automatiques TTC/HT',
                '• Workflow d\'approbation',
                '• Génération automatique de factures'
            ],
            '🧾 Gestion des Factures' => [
                '• Émission et suivi des factures',
                '• Gestion des échéances',
                '• Suivi des paiements',
                '• Relances automatiques'
            ]
        ];
        
        foreach ($features as $category => $items) {
            $this->line($category);
            foreach ($items as $item) {
                $this->line("  {$item}");
            }
            $this->line('');
        }
    }

    private function showTechnicalStack(): void
    {
        $this->info('⚙️ STACK TECHNIQUE');
        $this->line('─────────────────');
        
        $stack = [
            'Backend' => [
                '• Laravel 11.x (Framework PHP)',
                '• PHP 8.1+ avec strict typing',
                '• Eloquent ORM pour la base de données',
                '• Spatie Permission pour les rôles'
            ],
            'Frontend' => [
                '• Vue.js 3 avec TypeScript',
                '• Inertia.js pour SPA sans API',
                '• Tailwind CSS pour le design',
                '• Vite pour le build et hot reload'
            ],
            'Base de données' => [
                '• Structure complète avec 47 tables',
                '• Relations optimisées',
                '• Migrations et seeders',
                '• Index de performance'
            ],
            'Architecture' => [
                '• Modèle MVC respecté',
                '• Services pour la logique métier',
                '• Contrôleurs final et read-only',
                '• Validation avec Form Requests'
            ]
        ];
        
        foreach ($stack as $category => $items) {
            $this->line("<fg=yellow>{$category}:</>");
            foreach ($items as $item) {
                $this->line("  {$item}");
            }
            $this->line('');
        }
    }

    private function showAccessInfo(): void
    {
        $this->info('🔐 INFORMATIONS D\'ACCÈS');
        $this->line('─────────────────────');
        
        $this->line('<fg=green>Serveurs de développement:</>');
        $this->line('  • Laravel: http://127.0.0.1:8000');
        $this->line('  • Vite: http://localhost:5174');
        $this->line('');
        
        $this->line('<fg=green>Utilisateur administrateur:</>');
        $this->line('  • Email: admin@climdb.fr');
        $this->line('  • Mot de passe: password');
        $this->line('  • Rôle: admin (accès complet)');
        $this->line('');
        
        $this->line('<fg=green>Utilisateurs test:</>');
        $this->line('  • user@climdb.fr (utilisateur standard)');
        $this->line('  • tech@climdb.fr (technicien)');
        $this->line('  • Mot de passe: password');
        $this->line('');
    }

    private function showUsageGuide(): void
    {
        $this->info('📚 GUIDE D\'UTILISATION');
        $this->line('─────────────────────');
        
        $this->line('<fg=yellow>Pour démarrer l\'application:</>');
        $this->line('  1. Terminal 1: php artisan serve');
        $this->line('  2. Terminal 2: npm run dev');
        $this->line('  3. Navigateur: http://127.0.0.1:8000');
        $this->line('');
        
        $this->line('<fg=yellow>Navigation selon les rôles:</>');
        $this->line('  • Utilisateurs connectés: Dashboard, Pannes, Interventions');
        $this->line('  • Administrateurs: Toutes les sections + gestion admin');
        $this->line('');
        
        $this->line('<fg=yellow>Sections administrateur:</>');
        $this->line('  • Clients: Gestion complète des clients');
        $this->line('  • Sites: Gestion des sites clients');
        $this->line('  • Équipements: Inventaire et maintenance');
        $this->line('  • Devis: Création et suivi commercial');
        $this->line('  • Factures: Gestion de facturation');
        $this->line('');
        
        $this->line('<fg=yellow>Commandes utiles:</>');
        $this->line('  • php artisan test:application (test complet)');
        $this->line('  • php artisan app:summary (ce résumé)');
        $this->line('  • php artisan migrate:fresh --seed (reset BDD)');
        $this->line('');
    }
}
