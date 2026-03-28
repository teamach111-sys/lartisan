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
        Schema::table('signalement_produits', function (Blueprint $table) {
            $table->string('produit_nom')->nullable()->after('produit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signalement_produits', function (Blueprint $table) {
            $table->dropColumn('produit_nom');
        });
    }
};
