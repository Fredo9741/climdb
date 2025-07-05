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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->nullable();
            $table->float('latitude')->nullable(); // NOUVEAU
            $table->float('longitude')->nullable(); // NOUVEAU

            // Clé étrangère vers la table 'clients'
            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->onDelete('cascade'); // Si un client est supprimé, ses sites sont aussi supprimés.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};