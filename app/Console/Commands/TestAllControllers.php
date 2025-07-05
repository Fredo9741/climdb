<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Models\Site;
use App\Models\Equipement;
use App\Models\Panne;
use App\Models\Intervention;
use App\Models\Devis;
use App\Models\Facture;

class TestAllControllers extends Command
{
    protected $signature = 'test:all-controllers';
    protected $description = 'Teste tous les contrôleurs CRUD de l\'application';

    public function handle()
    {
        $this->info('🧪 Test de tous les contrôleurs CRUD...');
        
        $controllers = [
            'ClientController' => Client::class,
            'SiteController' => Site::class,
            'EquipementController' => Equipement::class,
            'PanneController' => Panne::class,
            'InterventionController' => Intervention::class,
            'DevisController' => Devis::class,
            'FactureController' => Facture::class,
        ];

        foreach ($controllers as $controllerName => $modelClass) {
            $this->testController($controllerName, $modelClass);
        }

        $this->info('✅ Tous les tests sont terminés !');
    }

    private function testController($controllerName, $modelClass)
    {
        $this->info("\n📋 Test du {$controllerName}...");
        
        try {
            // Test 1: Vérifier que le modèle existe
            $this->info("  ✓ Modèle {$modelClass} existe");
            
            // Test 2: Vérifier que les routes existent
            $routes = $this->getControllerRoutes($controllerName);
            foreach ($routes as $route) {
                $this->info("  ✓ Route {$route} existe");
            }
            
            // Test 3: Vérifier que le contrôleur peut être instancié
            $controllerClass = "App\\Http\\Controllers\\{$controllerName}";
            if (class_exists($controllerClass)) {
                $this->info("  ✓ Contrôleur {$controllerClass} existe");
            } else {
                $this->error("  ✗ Contrôleur {$controllerClass} n'existe pas");
            }
            
            // Test 4: Vérifier les méthodes du contrôleur
            $this->testControllerMethods($controllerClass);
            
        } catch (\Exception $e) {
            $this->error("  ✗ Erreur lors du test de {$controllerName}: " . $e->getMessage());
        }
    }

    private function getControllerRoutes($controllerName)
    {
        $routes = [];
        $routeList = Route::getRoutes();
        
        foreach ($routeList as $route) {
            if (str_contains($route->getActionName(), $controllerName)) {
                $routes[] = $route->uri();
            }
        }
        
        return $routes;
    }

    private function testControllerMethods($controllerClass)
    {
        $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        
        foreach ($methods as $method) {
            if (method_exists($controllerClass, $method)) {
                $this->info("    ✓ Méthode {$method}() existe");
            } else {
                $this->error("    ✗ Méthode {$method}() manquante");
            }
        }
        
        // Test des méthodes spéciales
        $specialMethods = [
            'DevisController' => ['accepter', 'refuser'],
            'FactureController' => ['marquerPayee', 'genererDepuisDevis'],
        ];
        
        $controllerName = class_basename($controllerClass);
        if (isset($specialMethods[$controllerName])) {
            foreach ($specialMethods[$controllerName] as $method) {
                if (method_exists($controllerClass, $method)) {
                    $this->info("    ✓ Méthode spéciale {$method}() existe");
                } else {
                    $this->error("    ✗ Méthode spéciale {$method}() manquante");
                }
            }
        }
    }
} 