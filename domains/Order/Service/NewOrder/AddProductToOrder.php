<?php

namespace Foodics\Order\Service\NewOrder;

use App\Models\Order,
    App\Models\Product,
    App\Models\ProductIngredient,
    Foodics\Order\Repository\OrderProductIngredientRepository,
    Foodics\Order\Repository\OrderProductRepository;

class AddProductToOrder
{

    public function __construct(
        private OrderProductRepository $orderProductRepo,
        private OrderProductIngredientRepository $orderProductIngredientRepo,
        private ProductIngredient $productIngredient
    ) {}

    public function execute(Order $order,Product $product): void
    {
        $orderProduct = $this->orderProductRepo->createProductOrder($order, $product);

        $productIngredients = $this->productIngredient->where('product_id', $product->id)->get();
        foreach ($productIngredients as $productIngredient){
            $this->orderProductIngredientRepo->addIngredientToOrder($productIngredient, $orderProduct);
        }
    }
}
