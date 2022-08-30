<?php

namespace Database\Seeders;

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
        DB::table('product_ingredients')->insert([
            'product_id' => 1,
            'ingredient_id' => 1,
            'quantity' => 150,
        ]);
        DB::table('product_ingredients')->insert([
            'product_id' => 1,
            'ingredient_id' => 2,
            'quantity' => 30,
        ]);
        DB::table('product_ingredients')->insert([
            'product_id' => 1,
            'ingredient_id' => 3,
            'quantity' => 20,
        ]);
    }
}
