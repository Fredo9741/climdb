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
        Schema::create('inventaire_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piece_detachee_id')
                  ->constrained('pieces_detachees')
                  ->onDelete('cascade');
            $table->foreignId('site_id') // Peut représenter un bureau ou un camion si modélisé comme un site
                  ->constrained('sites')
                  ->onDelete('cascade');
            $table->integer('quantite_disponible')->default(0);
            $table->unique(['piece_detachee_id', 'site_id']); // Une pièce, un site
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaire_pieces');
    }
};