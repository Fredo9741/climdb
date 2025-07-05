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
        Schema::create('mouvements_gaz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')
                  ->constrained('equipements')
                  ->onDelete('cascade');
            $table->foreignId('type_gaz_id')
                  ->constrained('types_gaz')
                  ->onDelete('restrict');
            $table->foreignId('intervention_id')
                  ->nullable()
                  ->constrained('interventions')
                  ->onDelete('set null');
            $table->foreignId('user_id') // Qui a effectué le mouvement
                  ->constrained('users')
                  ->onDelete('restrict'); // Un mouvement est lié à un utilisateur
            $table->string('type_mouvement'); // Ex: "ajout", "retrait", "récupération"
            $table->float('quantite_kg');
            $table->dateTime('date_mouvement');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_gaz');
    }
};