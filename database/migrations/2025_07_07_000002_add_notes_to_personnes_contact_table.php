<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnes_contact', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('telephone');
        });
    }

    public function down(): void
    {
        Schema::table('personnes_contact', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}; 