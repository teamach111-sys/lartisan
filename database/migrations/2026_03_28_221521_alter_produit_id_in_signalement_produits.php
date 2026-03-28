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
            // Under SQLite (Laravel 11+ natively handles these operations, drops constrain/recreates table if needed)
            $table->dropForeign(['produit_id']);
        });

        Schema::table('signalement_produits', function (Blueprint $table) {
            $table->unsignedBigInteger('produit_id')->nullable()->change();
            $table->foreign('produit_id')->references('id')->on('produits')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signalement_produits', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
        });
        
        Schema::table('signalement_produits', function (Blueprint $table) {
            $table->unsignedBigInteger('produit_id')->change();
            $table->foreign('produit_id')->references('id')->on('produits')->cascadeOnDelete();
        });
    }
};
