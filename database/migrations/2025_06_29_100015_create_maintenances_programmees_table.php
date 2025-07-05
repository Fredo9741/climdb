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
        Schema::create('maintenances_programmees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')
                  ->constrained('equipements')
                  ->onDelete('cascade');
            $table->string('type_maintenance'); // Ex: "Préventive annuelle", "Vérification trimestrielle"
            $table->text('description')->nullable();
            $table->dateTime('date_prevue');
            $table->string('frequence')->nullable(); // Ex: "annuelle", "trimestrielle", "mensuelle"
            $table->string('statut')->default('planifiee'); // Ex: "planifiee", "realisee", "reportee", "annulee"
            $table->foreignId('user_id') // Technicien assigné si déjà défini
                  ->nullable()
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
        Schema::dropIfExists('maintenances_programmees');
    }
};