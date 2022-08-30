<?php

namespace App\Console\Commands;

use Foodics\Order\Repository\OrderRepository;
use Foodics\Order\Service\FailedOrder\ReturnIngredientToStock;
use Illuminate\Console\Command;

class FailedOrdersCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'failed-orders-cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Return the ingredients to stock if the order fail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        OrderRepository $orderRepository,
        ReturnIngredientToStock $returnIngredientToStock
    ) {
        $failedOrders = $orderRepository->getFailedOrders();
        foreach ($failedOrders as $failedOrder) {
            $returnIngredientToStock->execute($failedOrder);
        }
    }
}
