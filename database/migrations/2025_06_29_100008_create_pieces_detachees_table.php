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
        Schema::create('pieces_detachees', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('reference_piece')->unique();
            $table->text('description')->nullable();
            $table->decimal('prix_unitaire', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces_detachees');
    }
};
