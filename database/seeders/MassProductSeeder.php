<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Ville;
use Illuminate\Support\Str;

class MassProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Prerequisites
        $categorie = Categorie::first() ?? Categorie::create(['nom' => 'Artisanat', 'slug' => 'artisanat']);
        $ville = Ville::first() ?? Ville::create(['nom' => 'Marrakech']);

        $this->command->info("Seeding products for all users (Ultra-Efficient Mode)...");

        $imagePath = 'produits/seed_image.jpg';
        $imagesArray = json_encode([$imagePath, $imagePath, $imagePath, $imagePath, $imagePath]);
        $total = User::count();
        $processed = 0;

        // Use chunkById to keep memory usage low (only 500 users in RAM at a time)
        User::where('id', '>', 5)->orderBy('id')->chunkById(1000, function($users) use ($categorie, $ville, $imagesArray, &$processed, $total) {
            $data = [];
            foreach ($users as $user) {
                $titre = "Artisanat d'exception par " . $user->name;
                
                $data[] = [
                    'vendeur_id' => $user->id,
                    'categorie_id' => $categorie->id,
                    'titre' => $titre,
                    'slug' => Str::slug($titre) . '-' . $user->id . '-' . bin2hex(random_bytes(2)),
                    'description' => "Une création artisanale unique réalisée par " . $user->name . ". Fait main avec passion.",
                    'prix' => rand(200, 5000),
                    'ville_produit' => $ville->nom,
                    'images' => $imagesArray,
                    'etat_produit' => 'neuf',
                    'etat_moderation' => 'valide',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            \Illuminate\Support\Facades\DB::table('produits')->insert($data);
            $processed += $users->count();
            $this->command->comment("Progress: $processed / $total products inserted...");
        });

        $this->command->info("Ultra-Mass product seeding complete!");
    }
}
