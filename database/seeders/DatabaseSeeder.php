<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run():void
    {
         User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
         ]);
         $this->call([
             IngredientSeeder::class,
             ProductSeeder::class,
             ProductIngredientSeeder::class,
         ]);
    }
}
