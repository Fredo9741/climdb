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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nom_document');
            $table->string('chemin_stockage'); // Chemin relatif ou complet du fichier
            $table->string('type_document'); // Ex: "manuel", "schéma", "facture", "rapport"
            $table->text('description')->nullable();
            // Colonnes pour la relation polymorphique
            $table->morphs('element'); // element_id (int) et element_type (string)
            $table->foreignId('user_id') // Qui a uploadé le document
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
        Schema::dropIfExists('documents');
    }
};