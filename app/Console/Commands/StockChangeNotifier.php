<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use Illuminate\Console\Command;

class StockChangeNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock-change-notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Ingredient $ingredient)
    {
        $ingredientsReachedLimit = $ingredient
            ->whereColumn('current_quantity', '<','init_quantity')
            ->where('notified', 0)
            ->get();
        foreach ($ingredientsReachedLimit as $ingredientReachedLimit) {
            /**
             * @todo send email
             */
            $ingredientReachedLimit->notified = 1;
            $ingredientReachedLimit->save();
        }
    }
}
