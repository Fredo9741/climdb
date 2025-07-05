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
        Schema::create('personne_contact_site', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personne_contact_id')
                  ->constrained('personnes_contact')
                  ->onDelete('cascade');
            $table->foreignId('site_id')
                  ->constrained('sites')
                  ->onDelete('cascade');
            $table->unique(['personne_contact_id', 'site_id']); // Empêche le doublon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personne_contact_site');
    }
};