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
        Schema::create('historiques_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') // Qui a effectué l'action
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->string('action_type'); // Ex: "création", "modification", "suppression", "connexion"
            $table->text('description'); // Détail de l'action
            $table->morphs('element'); // L'élément sur lequel l'action a été effectuée (polymorphique)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques_actions');
    }
};