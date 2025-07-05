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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->onDelete('restrict'); // Ne pas supprimer un client si un devis existe
            $table->foreignId('site_id')
                  ->nullable()
                  ->constrained('sites')
                  ->onDelete('set null'); // Optionnel
            $table->string('numero_devis')->unique();
            $table->date('date_devis');
            $table->date('date_expiration')->nullable();
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->string('statut')->default('brouillon'); // Ex: "brouillon", "envoyé", "accepté", "refusé", "facturé"
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};