<?php

namespace Foodics\Order\Repository;

use App\Models\Ingredient;
use App\Models\OrderProductIngredient;
use App\Models\ProductIngredient, App\Models\OrderProduct;
use Foodics\Order\Service\QuantityCalculator\CalculatorFactory;
use Illuminate\Support\Facades\DB;

class OrderProductIngredientRepository
{

    public function __construct(
        private Ingredient $ingredient,
        private CalculatorFactory $calculatorFactory
    )
    {}

    public function addIngredientToOrder(
        ProductIngredient $productIngredient,
        OrderProduct $orderProduct,
        int $quantity
    ) {
        /**
         * @todo add the unitType into the Ingredient table
         */
        $calculator = $this->calculatorFactory->getCalculator('weight');
        try {
            DB::beginTransaction();
            $ingredient = $this->ingredient->find($productIngredient->ingredient_id);

            $deductQuantity = $productIngredient->quantity * $quantity;
            $newQuantity = $calculator->deduct($ingredient->quantity, $deductQuantity);
            $this->createNew($orderProduct, $productIngredient->quantity, 'reserved');

            $ingredient->quantity = $newQuantity;
            $ingredient->save();
            DB::commit();
            /**
             * @todo didn't catch the root exception but catch every one and handel every issue separated
             */
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function createNew(OrderProduct $orderProduct,int $quantity, string $status)
    {
        $orderProductIngredient = new OrderProductIngredient();
        $orderProductIngredient->order_product_id = $orderProduct->id;
        $orderProductIngredient->quantity = $quantity;
        $orderProductIngredient->status = $status;
        $orderProductIngredient->save();
    }
}
