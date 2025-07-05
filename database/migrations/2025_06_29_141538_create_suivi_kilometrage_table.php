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
        Schema::create('suivi_kilometrage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')
                  ->constrained('vehicules')
                  ->onDelete('cascade');
            $table->foreignId('user_id') // Technicien qui a relevé le kilométrage
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->decimal('kilometrage', 10, 0);
            $table->dateTime('date_releve');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_kilometrage');
    }
};