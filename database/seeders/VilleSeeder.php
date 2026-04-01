<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
$villes = [
    // Grand Sud & Souss
    'Agadir', 'Aït Melloul', 'Inezgane', 'Taroudant', 'Tiznit', 'Tafraout', 'Guelmim', 'Tan-Tan', 'Laâyoune', 'Dakhla', 'Tata', 'Assa',

    // Centre & Haouz
    'Marrakech', 'Essaouira', 'Safi', 'El Kelaâ des Sraghna', 'Benguérir', 'Chichaoua', 'Tahannaout',

    // Grand Casablanca & Chaouia
    'Casablanca', 'Mohammédia', 'Settat', 'Berrechid', 'Benslimane', 'El Jadida', 'Azemmour', 'Sidi Bennour',

    // Rabat, Salé & Kénitra
    'Rabat', 'Salé', 'Témara', 'Skhirat', 'Kénitra', 'Sidi Kacem', 'Sidi Slimane', 'Khémisset',

    // Nord & Rif
    'Tanger', 'Tétouan', 'Chefchaouen', 'Larache', 'Ksar El Kébir', 'Asilah', 'Al Hoceima', 'Ouazzane',

    // Oriental
    'Oujda', 'Nador', 'Berkane', 'Taourirt', 'Guercif', 'Jerada', 'Figuig', 'Saïdia',

    // Fès, Meknès & Moyen Atlas
    'Fès', 'Meknès', 'Ifrane', 'Azrou', 'Sefrou', 'Taza', 'Moulay Idriss Zerhoun', 'El Hajeb',

    // Tadla & Azilal
    'Béni Mellal', 'Fquih Ben Salah', 'Khouribga', 'Oued Zem', 'Kasba Tadla', 'Azilal', 'Demnate',

    // Drâa-Tafilalet
    'Errachidia', 'Ouarzazate', 'Zagora', 'Tinghir', 'Midelt', 'Rissani', 'Arfoud'
];
        foreach ($villes as $ville) {
            \App\Models\Ville::firstOrCreate(['nom' => $ville]);
        }
    }
}
