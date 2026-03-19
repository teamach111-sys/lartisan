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
    Schema::create('signalement_produits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
        $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
        
        // Type de signalement (ex: Arnaque, Doublon, etc.)
        $table->string('type_signalement')->index(); 
        $table->text('details')->nullable(); // Précisions facultatives
        
        // Pour ton interface admin : savoir si tu as déjà traité le problème
        $table->boolean('est_traite')->default(false)->index();
        
        $table->timestamps();
        
        // Empêche le double signalement par la même personne
        $table->unique(['produit_id', 'utilisateur_id'], 'unique_report');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalement_produits');
    }
};
