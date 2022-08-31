<?php

namespace Foodics\Order\Service\FailedOrder;

use App\Models\Ingredient,
    Illuminate\Support\Facades\DB,
    App\Models\Order;

class ReturnIngredientToStock
{

    public function __construct(private Ingredient $ingredient) {}

    public function execute(Order $order): void
    {
        foreach ($order->orderProducts() as $orderProduct) {
            foreach ($orderProduct->orderProductIngredients() as $orderProductIngredient) {
                if ($orderProductIngredient->status === 'reverted') {
                    continue;
                }
                try {
                    DB::beginTransaction();
                    /**
                     * @var Ingredient $ingredient
                     */
                    $ingredient = $this->ingredient->find($orderProductIngredient->ingredient_id);

                    $ingredient->increase($orderProductIngredient->quantity);
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
