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
        Schema::create('user_habilitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('habilitation_id')->constrained()->onDelete('cascade');
            $table->date('date_obtention');
            $table->date('date_echeance');
            $table->string('numero_certificat')->nullable();
            $table->text('commentaires')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'habilitation_id']);
            $table->index('date_echeance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_habilitations');
    }
};
