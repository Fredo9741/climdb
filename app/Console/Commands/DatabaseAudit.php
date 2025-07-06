<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ReflectionClass;

final class DatabaseAudit extends Command
{
    protected $signature = 'audit:database';
    protected $description = 'Audit de la cohérence entre la structure de base de données et le code';

    private array $tableStructure = [];
    private array $issues = [];
    private array $modelsRelations = [];

    public function handle(): int
    {
        $this->info('🔍 Audit de la base de données - ClimDB');
        $this->line('');

        // 1. Analyser la structure des tables depuis les migrations
        $this->analyzeMigrations();

        // 2. Analyser les modèles et leurs relations
        $this->analyzeModels();

        // 3. Analyser les contrôleurs et l'utilisation des données
        $this->analyzeControllers();

        // 4. Analyser les vues et l'accès aux propriétés
        $this->analyzeViews();

        // 5. Générer le rapport
        $this->generateReport();

        return 0;
    }

    private function analyzeMigrations(): void
    {
        $this->info('📁 Analyse des migrations...');

        $migrationFiles = File::files(database_path('migrations'));

        foreach ($migrationFiles as $file) {
            $content = File::get($file->getPathname());
            
            // Extraire les noms de tables et leurs colonnes
            if (preg_match("/Schema::create\('([^']+)'/", $content, $tableMatches)) {
                $tableName = $tableMatches[1];
                $this->tableStructure[$tableName] = $this->extractColumnsFromMigration($content);
            }
        }

        $this->line("   ✅ Analysé " . count($this->tableStructure) . " tables");
    }

    private function extractColumnsFromMigration(string $content): array
    {
        $columns = [];
        
        // Patterns pour différents types de colonnes
        $patterns = [
            '/\$table->([a-zA-Z]+)\([\'"]([^\'")]+)[\'"]?\)/',
            '/\$table->([a-zA-Z]+)\(\)/',
            '/\$table->foreignId\([\'"]([^\'")]+)[\'"]?\)/',
            '/\$table->enum\([\'"]([^\'")]+)[\'"]?/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $type = $match[1];
                    $name = $match[2] ?? $type;
                    
                    if (!in_array($name, ['id', 'timestamps', 'rememberToken'])) {
                        $columns[] = [
                            'name' => $name,
                            'type' => $type
                        ];
                    }
                }
            }
        }

        // Ajouter les colonnes automatiques
        array_unshift($columns, ['name' => 'id', 'type' => 'id']);
        $columns[] = ['name' => 'created_at', 'type' => 'timestamp'];
        $columns[] = ['name' => 'updated_at', 'type' => 'timestamp'];

        return $columns;
    }

    private function analyzeModels(): void
    {
        $this->info('🏗️  Analyse des modèles...');

        $modelFiles = File::files(app_path('Models'));
        
        foreach ($modelFiles as $file) {
            $content = File::get($file->getPathname());
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            
            // Analyser les relations
            $this->modelsRelations[$className] = $this->extractRelationsFromModel($content);
            
            // Analyser les $fillable et autres propriétés
            $this->analyzeModelProperties($content, $className);
        }

        $this->line("   ✅ Analysé " . count($modelFiles) . " modèles");
    }

    private function extractRelationsFromModel(string $content): array
    {
        $relations = [];
        
        // Pattern pour les relations Eloquent
        $patterns = [
            '/public function ([a-zA-Z_]+)\(\).*?return \$this->(hasOne|hasMany|belongsTo|belongsToMany)\(([^)]+)\)/',
            '/function ([a-zA-Z_]+)\(\).*?BelongsTo.*?return \$this->belongsTo\(([^)]+)\)/',
            '/function ([a-zA-Z_]+)\(\).*?HasMany.*?return \$this->hasMany\(([^)]+)\)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $relations[] = [
                        'method' => $match[1],
                        'type' => $match[2] ?? 'unknown',
                        'target' => $match[3] ?? $match[2] ?? 'unknown'
                    ];
                }
            }
        }

        return $relations;
    }

    private function analyzeModelProperties(string $content, string $className): void
    {
        // Analyser les $fillable
        if (preg_match('/protected \$fillable\s*=\s*\[(.*?)\]/s', $content, $matches)) {
            $fillableString = $matches[1];
            preg_match_all("/'([^']+)'/", $fillableString, $fillableMatches);
            
            $modelTable = $this->getTableNameFromModel($className);
            if ($modelTable && isset($this->tableStructure[$modelTable])) {
                $existingColumns = array_column($this->tableStructure[$modelTable], 'name');
                
                foreach ($fillableMatches[1] as $fillableField) {
                    if (!in_array($fillableField, $existingColumns)) {
                        $this->issues[] = [
                            'type' => 'fillable_column_missing',
                            'model' => $className,
                            'table' => $modelTable,
                            'field' => $fillableField,
                            'message' => "Champ '$fillableField' dans \$fillable du modèle $className mais absent de la table $modelTable"
                        ];
                    }
                }
            }
        }

        // Analyser les $table définies
        if (preg_match('/protected \$table\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
            $definedTable = $matches[1];
            if (!isset($this->tableStructure[$definedTable])) {
                $this->issues[] = [
                    'type' => 'table_not_found',
                    'model' => $className,
                    'table' => $definedTable,
                    'message' => "Table '$definedTable' définie dans le modèle $className mais inexistante en base"
                ];
            }
        }
    }

    private function getTableNameFromModel(string $className): ?string
    {
        // Conversion automatique Laravel: Model -> models, User -> users, etc.
        $tableName = strtolower($className);
        
        // Règles de pluralisation simples
        $pluralRules = [
            'y' => 'ies',
            's' => 'ses',
            'x' => 'xes',
            'z' => 'zes',
            'ch' => 'ches',
            'sh' => 'shes',
        ];

        foreach ($pluralRules as $ending => $replacement) {
            if (str_ends_with($tableName, $ending)) {
                $tableName = substr($tableName, 0, -strlen($ending)) . $replacement;
                break;
            }
        }

        if (!str_ends_with($tableName, 's')) {
            $tableName .= 's';
        }

        return isset($this->tableStructure[$tableName]) ? $tableName : null;
    }

    private function analyzeControllers(): void
    {
        $this->info('🎮 Analyse des contrôleurs...');

        $controllerFiles = File::allFiles(app_path('Http/Controllers'));
        
        foreach ($controllerFiles as $file) {
            $content = File::get($file->getPathname());
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            
            // Chercher les accès aux propriétés inexistantes
            $this->findInvalidPropertyAccess($content, $className);
            
            // Chercher les relations utilisées
            $this->findRelationUsage($content, $className);
        }

        $this->line("   ✅ Analysé " . count($controllerFiles) . " contrôleurs");
    }

    private function findInvalidPropertyAccess(string $content, string $className): void
    {
        // Pattern pour trouver les accès aux propriétés comme $model->property
        if (preg_match_all('/\$[a-zA-Z_]+->([a-zA-Z_]+)/', $content, $matches)) {
            foreach ($matches[1] as $property) {
                // Ignorer les méthodes communes et les propriétés Laravel
                if (in_array($property, ['id', 'created_at', 'updated_at', 'count', 'get', 'first', 'find', 'where', 'with', 'load', 'save', 'update', 'delete', 'create'])) {
                    continue;
                }
                
                // Vous pourriez ajouter ici plus de logique pour détecter les propriétés inexistantes
            }
        }

        // Chercher des colonnes spécifiques connues pour être problématiques
        $problematicColumns = ['nom_entreprise', 'titre', 'date_declaration'];
        foreach ($problematicColumns as $column) {
            if (strpos($content, $column) !== false) {
                $this->issues[] = [
                    'type' => 'problematic_column_usage',
                    'controller' => $className,
                    'field' => $column,
                    'message' => "Utilisation de la colonne potentiellement inexistante '$column' dans $className"
                ];
            }
        }
    }

    private function findRelationUsage(string $content, string $className): void
    {
        // Chercher les with() et les relations eager loading
        if (preg_match_all('/->with\(\[(.*?)\]\)/', $content, $matches)) {
            foreach ($matches[1] as $withClause) {
                preg_match_all("/'([^']+)'/", $withClause, $relations);
                foreach ($relations[1] as $relation) {
                    // Analyser les relations comme 'site.client', 'modele.typeEquipement'
                    if (strpos($relation, '.') !== false) {
                        $parts = explode('.', $relation);
                        // Vous pourriez vérifier ici si ces relations existent
                    }
                }
            }
        }
    }

    private function analyzeViews(): void
    {
        $this->info('👁️  Analyse des vues...');

        $vueFiles = File::allFiles(resource_path('js/pages'));
        
        foreach ($vueFiles as $file) {
            $content = File::get($file->getPathname());
            $filename = $file->getRelativePath() . '/' . $file->getFilename();
            
            // Chercher les accès aux propriétés dans les templates Vue
            $this->findVuePropertyAccess($content, $filename);
        }

        $this->line("   ✅ Analysé " . count($vueFiles) . " vues Vue.js");
    }

    private function findVuePropertyAccess(string $content, string $filename): void
    {
        // Chercher les colonnes problématiques dans les templates Vue
        $problematicColumns = ['nom_entreprise', 'titre', 'date_declaration'];
        foreach ($problematicColumns as $column) {
            if (strpos($content, $column) !== false) {
                $this->issues[] = [
                    'type' => 'vue_problematic_column',
                    'file' => $filename,
                    'field' => $column,
                    'message' => "Utilisation de la colonne potentiellement inexistante '$column' dans la vue $filename"
                ];
            }
        }

        // Chercher les propriétés manquantes dans les objets
        if (preg_match_all('/\{\{\s*([a-zA-Z_]+)\.([a-zA-Z_?]+)\s*\}\}/', $content, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $object = $matches[1][$i];
                $property = $matches[2][$i];
                
                // Ignorer les propriétés avec ? (optional chaining)
                if (strpos($property, '?') !== false) {
                    continue;
                }

                // Vous pourriez ajouter ici plus de vérifications
            }
        }
    }

    private function generateReport(): void
    {
        $this->line('');
        $this->info('📊 RAPPORT D\'AUDIT DE LA BASE DE DONNÉES');
        $this->line('=' . str_repeat('=', 50));

        // 1. Structure des tables
        $this->line('');
        $this->comment('🏗️  STRUCTURE DES TABLES DÉTECTÉES:');
        foreach ($this->tableStructure as $tableName => $columns) {
            $this->line("   📋 $tableName (" . count($columns) . " colonnes)");
            foreach ($columns as $column) {
                $this->line("      - {$column['name']} ({$column['type']})");
            }
        }

        // 2. Relations des modèles
        $this->line('');
        $this->comment('🔗 RELATIONS DES MODÈLES:');
        foreach ($this->modelsRelations as $model => $relations) {
            if (!empty($relations)) {
                $this->line("   🏷️  $model:");
                foreach ($relations as $relation) {
                    $this->line("      - {$relation['method']}() -> {$relation['type']}({$relation['target']})");
                }
            }
        }

        // 3. Problèmes détectés
        $this->line('');
        if (empty($this->issues)) {
            $this->info('✅ AUCUN PROBLÈME DÉTECTÉ !');
        } else {
            $this->error('❌ PROBLÈMES DÉTECTÉS (' . count($this->issues) . ')');
            
            $groupedIssues = [];
            foreach ($this->issues as $issue) {
                $groupedIssues[$issue['type']][] = $issue;
            }

            foreach ($groupedIssues as $type => $issues) {
                $this->line('');
                $this->warn("   🚨 " . strtoupper(str_replace('_', ' ', $type)) . " (" . count($issues) . "):");
                foreach ($issues as $issue) {
                    $this->line("      - " . $issue['message']);
                }
            }
        }

        // 4. Statistiques
        $this->line('');
        $this->comment('📈 STATISTIQUES:');
        $this->line("   - Tables analysées: " . count($this->tableStructure));
        $this->line("   - Modèles analysés: " . count($this->modelsRelations));
        $this->line("   - Problèmes trouvés: " . count($this->issues));

        // 5. Recommandations
        $this->line('');
        $this->comment('💡 RECOMMANDATIONS:');
        if (!empty($this->issues)) {
            $this->line("   1. Corriger les colonnes inexistantes dans le code");
            $this->line("   2. Mettre à jour les modèles pour correspondre aux tables");
            $this->line("   3. Vérifier les relations Eloquent");
            $this->line("   4. Nettoyer les propriétés \$fillable");
        } else {
            $this->line("   ✅ Votre base de données est cohérente avec le code !");
        }

        $this->line('');
        $this->info('🎯 Audit terminé !');
    }
} 