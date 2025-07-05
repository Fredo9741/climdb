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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') // À quel utilisateur la notification est destinée
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('titre');
            $table->text('message');
            $table->string('type_notification')->default('info'); // Ex: "info", "alerte", "rappel", "urgence"
            $table->string('lien_associe')->nullable(); // URL interne vers la ressource concernée
            $table->timestamp('read_at')->nullable(); // Date/heure à laquelle la notification a été lue
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};