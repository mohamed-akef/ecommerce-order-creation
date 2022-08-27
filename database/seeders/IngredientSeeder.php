<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Ingredient::factory()->create([
            'name' => 'Beef',
            'quantity' => 20,
            'unit' => 'kg'
        ]);
        Ingredient::factory()->create([
            'name' => 'Cheese',
            'quantity' => 5,
            'unit' => 'kg'
        ]);
        Ingredient::factory()->create([
            'name' => 'Cheese',
            'quantity' => 1,
            'unit' => 'kg'
        ]);
    }
}
