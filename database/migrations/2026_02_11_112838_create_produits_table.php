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
    Schema::create('produits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendeur_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
        $table->string('titre');
        $table->string('slug')->unique()->index(); // Index crucial pour la vitesse
        $table->text('description')->nullable();
        $table->boolean('telephone_visible')->default(false);
        
        $table->decimal('prix', 12, 2)->index(); // Indexé pour tes filtres "Prix ↑ ↓"
        $table->string('ville_produit', 100)->index(); // Indexé pour ton filtre "Ville"
        
        $table->json('images')->nullable();
        
        // On remplace Enum par String pour plus de souplesse sur SQLite
        $table->string('etat_produit')->default('neuf'); 
        $table->string('etat_moderation')->default('en_attente'); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
