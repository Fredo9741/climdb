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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->string('modele');
            $table->string('immatriculation')->unique();
            $table->integer('annee_fabrication')->nullable();
            $table->decimal('kilometrage_actuel', 10, 0)->default(0); // Kilométrage entier
            $table->string('type_carburant')->nullable(); // Ex: "Essence", "Diesel", "Électrique"
            $table->date('date_acquisition')->nullable();
            $table->foreignId('statut_vehicule_id')
                ->constrained('statuts_vehicules')
                ->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
