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
        Schema::create('entretiens_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')
                  ->constrained('vehicules')
                  ->onDelete('cascade');
            $table->string('type_entretien'); // Ex: "Vidange", "Révision 30000km", "Réparation freins"
            $table->date('date_entretien');
            $table->decimal('kilometrage_entretien', 10, 0)->nullable();
            $table->text('description')->nullable();
            $table->decimal('cout', 8, 2)->nullable();
            $table->string('garage')->nullable(); // Nom du garage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens_vehicules');
    }
};