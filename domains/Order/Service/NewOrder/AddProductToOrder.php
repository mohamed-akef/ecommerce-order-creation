<?php

namespace Foodics\Order\Service\NewOrder;

use App\Models\Ingredient,
    App\Models\Order,
    App\Models\Product,
    Foodics\Order\Repository\OrderProductIngredientRepository,
    Foodics\Order\Repository\OrderProductRepository,
    Illuminate\Support\Facades\DB;
use App\Models\OrderProductIngredient;
use App\Models\ProductIngredient;

class AddProductToOrder
{

    public function __construct(
        private OrderProductRepository $orderProductRepo,
        private OrderProductIngredientRepository $orderProductIngredientRepo,
        private ProductIngredient $productIngredient,
        private Ingredient $ingredient
    ) {}

    public function execute(Order $order,Product $product, int $count): void
    {
        $orderProduct = $this->orderProductRepo->createOrderProduct($order, $product, $count);

        $productIngredients = $this->productIngredient->where('product_id', $product->id)->get();
        foreach ($productIngredients as $productIngredient) {
            try {
                DB::beginTransaction();

                /**
                 * @var Ingredient $ingredient
                 */
                $ingredient = $this->ingredient->find($productIngredient->ingredient_id);

                $deductQuantity = $productIngredient->quantity * $count;

                $this->orderProductIngredientRepo->createNew(
                    $orderProduct,
                    $deductQuantity,
                    OrderProductIngredient::STATUS_RESERVED
                );
                /**
                 * @note we can add the check to notifying admin here also, but it will not be accurate because of
                 *       failed orders.
                 */
                $ingredient->deduct($deductQuantity);
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
