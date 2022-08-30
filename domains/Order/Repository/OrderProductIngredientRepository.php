<?php

namespace Foodics\Order\Repository;

use App\Models\OrderProductIngredient;
use  App\Models\OrderProduct;

class OrderProductIngredientRepository
{

    public function __construct() {}

    public function createNew(OrderProduct $orderProduct,int $quantity, string $status)
    {
        $orderProductIngredient = new OrderProductIngredient();
        $orderProductIngredient->order_product_id = $orderProduct->id;
        $orderProductIngredient->quantity = $quantity;
        $orderProductIngredient->status = $status;
        $orderProductIngredient->save();
    }
}
