<?php

namespace Database\Seeders;

use App\Models\ProductIngredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @todo use laravel factory better than static values
         */
        ProductIngredient::factory()->create([
            'product_id' => 1,
            'ingredient_id' => 1,
            'quantity' => 150,
            'unit' => 'g'
        ]);
        ProductIngredient::factory()->create([
            'product_id' => 1,
            'ingredient_id' => 1,
            'quantity' => 150,
            'unit' => 'g'
        ]);
        ProductIngredient::factory()->create([
            'product_id' => 1,
            'ingredient_id' => 3,
            'quantity' => 20,
            'unit' => 'g'
        ]);
    }
}
