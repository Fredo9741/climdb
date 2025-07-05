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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('nom_fichier');
            $table->string('chemin_stockage'); // Chemin relatif ou complet où la photo est stockée
            $table->text('description')->nullable();
            // Colonnes pour la relation polymorphique (element_id et element_type)
            $table->morphs('element'); // Créera element_id (BIGINT UNSIGNED) et element_type (VARCHAR)
                                       // ex: element_id = 1, element_type = 'App\Models\Equipement'
            $table->foreignId('user_id') // Qui a uploadé la photo
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // Si l'utilisateur est supprimé, le lien est annulé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};