<?php

namespace Foodics\Order\Service;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductIngredient;

class AddProductToOrder
{

    public function __construct(private ProductIngredient $productIngredient)
    {
    }

    public function execute(Order $order,Product $product, int $quantity)
    {
        $productIngredients = ProductIngredient::where('product_id', $product->getId())->get();
        foreach ($productIngredients as $productIngredient){
            
        }
    }
}
