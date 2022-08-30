<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            'name' => 'Beef',
            'init_quantity' => 20000,
            'current_quantity' => 20000,
        ]);
        DB::table('ingredients')->insert([
            'name' => 'Cheese',
            'init_quantity' => 5000,
            'current_quantity' => 5000,
        ]);
        DB::table('ingredients')->insert([
            'name' => 'Cheese',
            'init_quantity' => 1000,
            'current_quantity' => 1000,
        ]);
    }
}
