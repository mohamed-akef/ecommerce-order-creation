<?php

namespace Foodics\Order\Service\NewOrder;

use App\Models\Ingredient;
use App\Models\Order,
    App\Models\Product,
    App\Models\ProductIngredient,
    Foodics\Order\Repository\OrderProductIngredientRepository,
    Foodics\Order\Repository\OrderProductRepository,
    Foodics\Order\Service\QuantityCalculator\CalculatorFactory,
    Illuminate\Support\Facades\DB;

class AddProductToOrder
{

    public function __construct(
        private OrderProductRepository $orderProductRepo,
        private OrderProductIngredientRepository $orderProductIngredientRepo,
        private ProductIngredient $productIngredient,
        private Ingredient $ingredient,
        private CalculatorFactory $calculatorFactory
    ) {}

    public function execute(Order $order,Product $product, int $quantity): void
    {
        $orderProduct = $this->orderProductRepo->createOrderProduct($order, $product, $quantity);

        $productIngredients = $this->productIngredient->where('product_id', $product->id)->get();
        foreach ($productIngredients as $productIngredient) {
            /**
             * @todo add the unitType into the Ingredient table
             */
            $calculator = $this->calculatorFactory->getCalculator('weight');
            try {
                DB::beginTransaction();
                $ingredient = $this->ingredient->find($productIngredient->ingredient_id);

                $deductQuantity = $productIngredient->quantity * $quantity;
                $newQuantity = $calculator->deduct($ingredient->current_quantity, $deductQuantity);
                $this->orderProductIngredientRepo->createNew($orderProduct, $deductQuantity, 'reserved');

                $ingredient->current_quantity = $newQuantity;
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
    }
}
