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

    public function handle(Ingredient $ingredient): void
    {
        /**
         * @todo add the pending orders also here to get more accurate notifying
         */
        $ingredientsReachedLimit = $ingredient
            ->whereColumn('current_quantity', '<','init_quantity/2')
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
