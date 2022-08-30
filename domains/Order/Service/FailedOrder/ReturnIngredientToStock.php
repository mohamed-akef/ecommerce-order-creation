<?php

namespace Foodics\Order\Service\FailedOrder;

use App\Models\Ingredient,
    Foodics\Order\Service\QuantityCalculator\CalculatorFactory,
    Illuminate\Support\Facades\DB,
    App\Models\Order;

class ReturnIngredientToStock
{

    public function __construct(
        private Ingredient $ingredient,
        private CalculatorFactory $calculatorFactory
    ) {}

    public function execute(Order $order): void
    {
        foreach ($order->orderProducts() as $orderProduct) {
            foreach ($orderProduct->orderProductIngredients() as $orderProductIngredient) {
                $calculator = $this->calculatorFactory->getCalculator('weight');
                try {
                    DB::beginTransaction();
                    $ingredient = $this->ingredient->find($orderProductIngredient->ingredient_id);

                    $newQuantity = $calculator->increase(
                        $orderProductIngredient->quantity,
                        $ingredient->current_quantity
                    );

                    $ingredient->current_quantity = $newQuantity;
                    $ingredient->save();

                    $orderProductIngredient->status = 'reverted';
                    $orderProductIngredient->save();
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            }
        }
        $order->status = 'canceled';
        $order->save();
    }
}
