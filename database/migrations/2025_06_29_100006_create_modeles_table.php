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
        Schema::create('modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_equipement_id') // Lien vers la catégorie générale (NOUVEAU)
                  ->constrained('types_equipements')
                  ->onDelete('restrict'); // Un modèle ne peut exister sans son type d'équipement
            $table->string('marque');
            $table->string('nom'); // Nom du modèle (ex: "FTXM50R", "Atlantic Naia 2")
            $table->string('reference_constructeur')->unique()->nullable(); // Réf. unique du fabricant
            $table->text('description')->nullable();
            $table->float('quantite_gaz_kg')->nullable(); // Quantité de gaz du modèle
            $table->foreignId('type_gaz_id')
                  ->nullable()
                  ->constrained('types_gaz')
                  ->onDelete('set null'); // Le type de gaz peut être nul si non pertinent

            $table->foreignId('modele_releve_defaut_id') // Modèle de relevé par défaut pour CE modèle (NOUVEAU)
                  ->nullable()
                  ->constrained('modeles_releves') // Cette contrainte fonctionnera si 'modeles_releves' est créé avant
                  ->onDelete('set null'); // Si un modèle de relevé par défaut est supprimé, il est retiré des modèles

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modeles');
    }
};