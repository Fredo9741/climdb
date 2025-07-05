<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_client_id')
                  ->nullable()
                  ->constrained('demandes_clients')
                  ->onDelete('set null');
            $table->foreignId('panne_id')
                  ->nullable()
                  ->constrained('pannes')
                  ->onDelete('set null');
            $table->foreignId('maintenance_programmee_id')
                  ->nullable()
                  ->constrained('maintenances_programmees')
                  ->onDelete('set null');

            // Champs de planification et d'exécution
            $table->dateTime('date_planifiee')->nullable(); // Quand l'intervention est prévue
            $table->dateTime('date_debut')->nullable(); // Quand le technicien a commencé
            $table->dateTime('date_fin')->nullable(); // Quand le technicien a terminé
            $table->foreignId('technicien_id') // Le technicien affecté à l'intervention
                  ->constrained('users')
                  ->onDelete('restrict'); // Une intervention doit avoir un technicien tant qu'elle est active

            $table->text('rapport')->nullable(); // Le rapport d'intervention
            $table->string('statut')->default('planifiee'); // Ex: "planifiee", "en_cours", "terminee", "annulee"

            $table->foreignId('modele_releve_utilise_id') // Le modèle de relevé choisi/utilisé pour cette intervention (NOUVEAU)
                  ->nullable()
                  ->constrained('modeles_releves') // Cette contrainte fonctionnera si 'modeles_releves' est créé avant
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};