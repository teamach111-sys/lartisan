<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create your specific admin/test account first
        User::factory()->create([
            'name' => 'Admin Artisan',
            'email' => 'admin@marche.ma',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // 2. Efficiently generate 5,000 random users
        // We use chunking to avoid memory exhaustion
        $totalUsers = 100000;
        $chunkSize = 1000;

        $this->command->info("Seeding $totalUsers users...");

        for ($i = 0; $i < $totalUsers; $i += $chunkSize) {
            User::factory($chunkSize)->create();
            $this->command->comment("Inserted " . ($i + $chunkSize) . " users...");
        }

        $this->command->info("Seeding complete!");
    }
}