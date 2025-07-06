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
        Schema::table('releves_mesures', function (Blueprint $table) {
            // SQLite doesn't support altering drop foreign directly; Laravel handles for other DBs.
            // So we first drop FK then change column.
            $table->dropForeign(['intervention_id']);
            $table->unsignedBigInteger('intervention_id')->nullable()->change();
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('releves_mesures', function (Blueprint $table) {
            $table->dropForeign(['intervention_id']);
            $table->unsignedBigInteger('intervention_id')->nullable(false)->change();
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
        });
    }
}; 