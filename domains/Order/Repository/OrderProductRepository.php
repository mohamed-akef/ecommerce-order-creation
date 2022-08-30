<?php

namespace Foodics\Order\Repository;

use \App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderProductRepository
{

    public function createProductOrder(Order $order, Product $product): OrderProduct
    {
        $orderProduct = new OrderProduct;
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $product->id;
        $orderProduct->save();

        return $orderProduct;
    }
}
