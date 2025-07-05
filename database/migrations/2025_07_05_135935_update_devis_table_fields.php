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
        Schema::table('devis', function (Blueprint $table) {
            // Renommer les champs existants pour correspondre au contrôleur
            $table->renameColumn('numero_devis', 'numero');
            $table->renameColumn('date_devis', 'date_creation');
            $table->renameColumn('date_expiration', 'date_validite');
            
            // Ajouter les nouveaux champs requis par le contrôleur
            $table->decimal('tva', 5, 2)->default(20.00)->after('montant_ttc');
            $table->text('conditions_paiement')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            // Restaurer les noms d'origine
            $table->renameColumn('numero', 'numero_devis');
            $table->renameColumn('date_creation', 'date_devis');
            $table->renameColumn('date_validite', 'date_expiration');
            
            // Supprimer les champs ajoutés
            $table->dropColumn(['tva', 'conditions_paiement']);
        });
    }
};
