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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
        $table->foreignId('expediteur_id')->constrained('users');
        
        $table->text('contenu');
        
        // Indispensable pour ton UI : afficher un point bleu ou "Vu"
        $table->boolean('est_lu')->default(false)->index(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
