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
        Schema::create('affectations_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')
                ->constrained('vehicules')
                ->onDelete('cascade');
            $table->foreignId('user_id') // Le technicien ou l'utilisateur affecté
                ->constrained('users')
                ->onDelete('cascade');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable(); // Peut être nulle si affectation en cours
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations_vehicules');
    }
};
