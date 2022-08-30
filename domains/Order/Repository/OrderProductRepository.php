<?php

namespace Foodics\Order\Repository;

use \App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderProductRepository
{

    public function createOrderProduct(Order $order, Product $product, int $quantity): OrderProduct
    {
        $orderProduct = new OrderProduct;
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $product->id;
        $orderProduct->quantity = $quantity;
        $orderProduct->save();

        return $orderProduct;
    }
}
