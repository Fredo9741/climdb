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
        Schema::create('pannes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')
                ->constrained('equipements')
                ->onDelete('cascade');
            $table->text('description_panne');
            $table->text('actions_correctives')->nullable();
            $table->foreignId('statut_demande_id')
                ->default(1) // Par défaut "En attente"
                ->constrained('statuts_demandes')
                ->onDelete('restrict');
            $table->enum('priorite', ['faible', 'moyenne', 'haute', 'urgente'])
                ->default('moyenne'); // Priorité par défaut
            $table->dateTime('date_constat');
            $table->dateTime('date_resolution')->nullable();
            $table->foreignId('user_id')->nullable() // Qui a enregistré la panne (souvent le technicien ou client)
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pannes');
    }
};
