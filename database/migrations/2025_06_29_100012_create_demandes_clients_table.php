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
        Schema::create('demandes_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')
                ->constrained('sites')
                ->onDelete('cascade');
            $table->foreignId('equipement_id')
                ->constrained('equipements')
                ->onDelete('cascade');
            $table->foreignId('personne_contact_id')
                ->constrained('personnes_contact')
                ->onDelete('cascade');
            $table->foreignId('statut_id')
                ->constrained('statuts_demandes')
                ->onDelete('restrict');
            $table->text('description'); // Description détaillée de la demande du client
            $table->date('date_soumission');
            $table->date('date_cloture')->nullable(); // Date à laquelle la demande a été résolue ou annulée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_clients');
    }
};
