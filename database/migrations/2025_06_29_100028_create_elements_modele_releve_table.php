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
        Schema::create('elements_modele_releve', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modele_releve_id')
                  ->constrained('modeles_releves')
                  ->onDelete('cascade'); // Si un modèle de relevé est supprimé, ses éléments le sont aussi.

            $table->string('type_mesure'); // Ex: "Température", "Pression", "Débit d'air"
            $table->string('unite_attendue')->nullable(); // Unité suggérée ou obligatoire (ex: "°C", "bar", "L/s")
            $table->string('emplacement_suggerer')->nullable(); // Emplacement suggéré (ex: "Entrée compresseur")
            $table->float('valeur_min_attendue')->nullable(); // Plage de valeurs attendues pour validation
            $table->float('valeur_max_attendue')->nullable(); // Plage de valeurs attendues pour validation
            $table->boolean('obligatoire')->default(false); // Si ce relevé est obligatoire
            $table->integer('ordre')->nullable(); // Ordre d'affichage dans le formulaire
            $table->text('commentaire_guide')->nullable(); // Aide pour le technicien
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elements_modele_releve');
    }
};