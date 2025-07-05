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
        Schema::table('types_gaz', function (Blueprint $table) {
            $table->integer('gwp')->nullable()->after('nom')->comment('Global Warming Potential');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types_gaz', function (Blueprint $table) {
            $table->dropColumn('gwp');
        });
    }
};
