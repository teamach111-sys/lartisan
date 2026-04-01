<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
$categories = [
    // L'Art du Fil (Textiles & Weaving)
    'Tapis Berbères', 'Tapis de Rabat', 'Handira', 'Couvertures en Laine', 'Djellabas Artisanales', 'Caftans de Luxe', 'Gandouras', 'Jabador',

    // La Broderie (Embroidery Styles)
    'Broderie de Fès (Tarz Fessi)', 'Broderie de Meknès', 'Broderie de Rabat', 'Tarz El-Ghorza', 'R’ndha',

    // La Poterie & Céramique (Clay & Earth)
    'Poterie de Safi', 'Poterie de Fès (Zellige)', 'Céramique de Zagora', 'Tagines en Terre Cuite', 'Vases émaillés',

    // Le Travail du Cuir (Leatherwork)
    'Babouches (Belgha)', 'Sacs en Cuir Nomade', 'Poufs en Cuir', 'Sellerie Traditionnelle', 'Ceintures Brodées',

    // Le Travail du Bois (Woodwork)
    'Bois de Thuya (Essaouira)', 'Bois de Cèdre Sculpté', 'Moucharabieh', 'Coffrets Incrustés', 'Tables en Marqueterie',

    // Métaux & Bijouterie (Metalwork & Jewelry)
    'Bijoux Berbères en Argent', 'Bijoux Émaillés de Tiznit', 'Cuivre Ciselé', 'Lanternes en Fer Forgé', 'Théières et Plateaux',

    // Art de Vivre & Cosmétique (Wellness & Lifestyle)
    'Huile d’Argan Cosmétique', 'Savon Noir & Gommage', 'Eau de Rose de Kelaat M\'Gouna', 'Paniers en Osier (Vannerie)', 'Bougies Artisanales'
];
        foreach ($categories as $categorie) {
            \App\Models\Categorie::firstOrCreate(['nom' => $categorie]);
        }
    }
}
