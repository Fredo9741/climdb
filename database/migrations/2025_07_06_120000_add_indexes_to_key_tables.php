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
        // Table: affectations_vehicules
        Schema::table('affectations_vehicules', function (Blueprint $table) {
            $table->index(['vehicule_id', 'date_debut'], 'affectations_vehicule_date_idx');
        });

        // Table: suivi_kilometrage
        Schema::table('suivi_kilometrage', function (Blueprint $table) {
            $table->index(['vehicule_id', 'date_releve'], 'suivi_km_date_idx');
        });

        // Table: mouvements_gaz
        Schema::table('mouvements_gaz', function (Blueprint $table) {
            $table->index(['equipement_id', 'date_mouvement'], 'mouvements_gaz_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectations_vehicules', function (Blueprint $table) {
            $table->dropIndex('affectations_vehicule_date_idx');
        });

        Schema::table('suivi_kilometrage', function (Blueprint $table) {
            $table->dropIndex('suivi_km_date_idx');
        });

        Schema::table('mouvements_gaz', function (Blueprint $table) {
            $table->dropIndex('mouvements_gaz_date_idx');
        });
    }
};