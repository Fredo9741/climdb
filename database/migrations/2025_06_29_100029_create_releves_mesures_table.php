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
        Schema::create('releves_mesures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')
                  ->constrained('interventions')
                  ->onDelete('cascade'); // Si une intervention est supprimée, ses relevés aussi
            $table->foreignId('equipement_id')
                  ->constrained('equipements')
                  ->onDelete('cascade'); // L'équipement sur lequel la mesure a été prise
            $table->foreignId('user_id') // Le technicien qui a pris la mesure
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->string('type_mesure'); // Ex: "Température", "Pression", "Débit d'air"
            $table->float('valeur');
            $table->string('unite'); // Unité de la valeur (ex: "°C", "bar", "L/s")

            $table->dateTime('date_mesure'); // Date et heure de la prise de mesure
            $table->string('emplacement_mesure')->nullable(); // Où la mesure a été prise sur l'équipement
            $table->text('commentaire')->nullable(); // Notes du technicien sur ce relevé

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releves_mesures');
    }
};