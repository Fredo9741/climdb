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
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie')->unique();
            $table->string('nom'); // Dénomination client/interne
            $table->text('description')->nullable();
            $table->date('date_installation')->nullable();
            $table->date('date_derniere_maintenance')->nullable();
            $table->string('localisation_precise')->nullable(); // Ex: "Bureau 3, près de la fenêtre"
            $table->string('etat')->default('actif'); // Ex: actif, inactif, en panne, en attente

            // Clés étrangères
            $table->foreignId('site_id')
                ->constrained('sites')
                ->onDelete('cascade'); // Si un site est supprimé, ses équipements sont aussi supprimés.

            $table->foreignId('modele_id')
                ->constrained('modeles')
                ->onDelete('restrict'); // Un équipement ne peut exister sans son modèle.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
