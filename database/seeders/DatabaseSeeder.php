<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            WSInstanceSeeder::class,
            WSApplicationSeeder::class,
            WSAgentSeeder::class,
            WSServiceSeeder::class,
            WSProfileSeeder::class,
            UserSeeder::class,
        ]);
    }
}
