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
        Schema::create('devis_equipements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')
                ->constrained('devis')
                ->onDelete('cascade');
            $table->foreignId('equipement_id')
                ->constrained('equipements')
                ->onDelete('cascade');
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('quantite')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();

            // Index pour éviter les doublons
            $table->unique(['devis_id', 'equipement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_equipements');
    }
};
