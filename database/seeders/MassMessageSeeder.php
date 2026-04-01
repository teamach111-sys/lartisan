<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produit;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MassMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Simulating mass messaging (499 conversations)...");

        // 1. Fetch 1,000 users (pairs of 2)
        $users = User::where('id', '>', 5)->orderBy('id')->take(1000)->get();
        if ($users->count() < 2) {
            $this->command->error("Not enough users to simulate messaging!");
            return;
        }

        $pairs = $users->chunk(2);
        $totalPairs = $pairs->count();

        foreach ($pairs as $index => $pair) {
            if ($pair->count() < 2) continue;

            $vendeur = $pair->first();
            $acheteur = $pair->last();

            // Find a product owned by the vendeur
            $produit = Produit::where('vendeur_id', $vendeur->id)->first();
            if (!$produit) continue;

            // Create Conversation
            $conversation = Conversation::create([
                'produit_id' => $produit->id,
                'acheteur_id' => $acheteur->id,
                'last_message_at' => now(),
            ]);

            // Create Message
            Message::create([
                'conversation_id' => $conversation->id,
                'expediteur_id' => $acheteur->id,
                'contenu' => "Bonjour! Je suis très intéressé par votre article '" . $produit->titre . "'. Est-il toujours disponible ?",
            ]);

            if (($index + 1) % 50 === 0) {
                $this->command->comment("Generated " . ($index + 1) . " / $totalPairs conversations...");
            }
        }

        $this->command->info("Mass messaging simulation complete!");
    }
}
