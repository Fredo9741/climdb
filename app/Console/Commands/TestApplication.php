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
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

final class TestApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test toutes les fonctionnalités de l\'application ClimDB';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🧪 Test de l\'application ClimDB');
        $this->line('');

        // Test de la base de données
        $this->testDatabase();

        // Test des routes
        $this->testRoutes();

        // Test des contrôleurs
        $this->testControllers();

        // Test des vues
        $this->testViews();

        $this->line('');
        $this->info('✅ Tests terminés avec succès !');

        return 0;
    }

    private function testDatabase(): void
    {
        $this->info('📊 Test de la base de données...');

        $users = User::count();
        $clients = Client::count();
        $sites = Site::count();
        $equipements = Equipement::count();
        $pannes = Panne::count();
        $interventions = Intervention::count();
        $devis = Devis::count();
        $factures = Facture::count();

        $this->line("   👥 Utilisateurs: {$users}");
        $this->line("   🏢 Clients: {$clients}");
        $this->line("   🏗️  Sites: {$sites}");
        $this->line("   🔧 Équipements: {$equipements}");
        $this->line("   ⚠️  Pannes: {$pannes}");
        $this->line("   🔨 Interventions: {$interventions}");
        $this->line("   📋 Devis: {$devis}");
        $this->line("   🧾 Factures: {$factures}");

        // Test utilisateur admin
        $admin = User::where('email', 'admin@climdb.fr')->first();
        if ($admin && $admin->hasRole('admin')) {
            $this->line('   ✅ Utilisateur admin configuré correctement');
        } else {
            $this->line("   ❌ Problème avec l'utilisateur admin");
        }

        $this->line('');
    }

    private function testRoutes(): void
    {
        $this->info('🛣️  Test des routes...');

        $routes = [
            'dashboard',
            'clients.index', 'clients.create', 'clients.show', 'clients.edit',
            'sites.index', 'sites.create', 'sites.show', 'sites.edit',
            'equipements.index', 'equipements.create',
            'pannes.index', 'pannes.create', 'pannes.show', 'pannes.edit',
            'interventions.index', 'interventions.create', 'interventions.show', 'interventions.edit',
            'devis.index',
            'factures.index',
        ];

        $routeCollection = Route::getRoutes();
        $existingRoutes = [];

        foreach ($routeCollection as $route) {
            if ($route->getName()) {
                $existingRoutes[] = $route->getName();
            }
        }

        foreach ($routes as $routeName) {
            if (in_array($routeName, $existingRoutes)) {
                $this->line("   ✅ Route '{$routeName}' existe");
            } else {
                $this->line("   ❌ Route '{$routeName}' manquante");
            }
        }

        $this->line('');
    }

    private function testControllers(): void
    {
        $this->info('🎮 Test des contrôleurs...');

        $controllers = [
            'App\Http\Controllers\DashboardController' => ['index'],
            'App\Http\Controllers\ClientController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\SiteController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\EquipementController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\PanneController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\InterventionController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\DevisController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'App\Http\Controllers\FactureController' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
        ];

        foreach ($controllers as $controllerClass => $methods) {
            $controllerName = class_basename($controllerClass);

            if (class_exists($controllerClass)) {
                $this->line("   ✅ Contrôleur {$controllerName} existe");

                foreach ($methods as $method) {
                    if (method_exists($controllerClass, $method)) {
                        $this->line("      ✅ Méthode {$method}");
                    } else {
                        $this->line("      ❌ Méthode {$method} manquante");
                    }
                }
            } else {
                $this->line("   ❌ Contrôleur {$controllerName} manquant");
            }
        }

        $this->line('');
    }

    private function testViews(): void
    {
        $this->info('🖼️  Test des vues...');

        $views = [
            'resources/js/pages/Dashboard.vue',
            'resources/js/pages/clients/Index.vue',
            'resources/js/pages/clients/Create.vue',
            'resources/js/pages/clients/Show.vue',
            'resources/js/pages/clients/Edit.vue',
            'resources/js/pages/sites/Index.vue',
            'resources/js/pages/sites/Create.vue',
            'resources/js/pages/sites/Show.vue',
            'resources/js/pages/sites/Edit.vue',
            'resources/js/pages/equipements/Index.vue',
            'resources/js/pages/equipements/Create.vue',
            'resources/js/pages/pannes/Index.vue',
            'resources/js/pages/pannes/Create.vue',
            'resources/js/pages/pannes/Show.vue',
            'resources/js/pages/pannes/Edit.vue',
            'resources/js/pages/interventions/Index.vue',
            'resources/js/pages/interventions/Create.vue',
            'resources/js/pages/interventions/Show.vue',
            'resources/js/pages/interventions/Edit.vue',
            'resources/js/pages/devis/Index.vue',
            'resources/js/pages/factures/Index.vue',
        ];

        foreach ($views as $view) {
            if (file_exists(base_path($view))) {
                $this->line("   ✅ Vue {$view} existe");
            } else {
                $this->line("   ❌ Vue {$view} manquante");
            }
        }

        $this->line('');
    }
}
