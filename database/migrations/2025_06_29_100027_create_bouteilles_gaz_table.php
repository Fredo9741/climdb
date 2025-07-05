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
        Schema::create('bouteilles_gaz', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie')->unique();
            $table->foreignId('type_gaz_id')
                  ->constrained('types_gaz')
                  ->onDelete('restrict');
            $table->decimal('capacite_kg', 8, 2);
            $table->decimal('poids_actuel_kg', 8, 2);
            $table->foreignId('statut_bouteille_id')
                  ->constrained('statuts_bouteilles')
                  ->onDelete('restrict');
            $table->foreignId('user_id') // Le technicien qui a la bouteille si elle est affectée
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bouteilles_gaz');
    }
};