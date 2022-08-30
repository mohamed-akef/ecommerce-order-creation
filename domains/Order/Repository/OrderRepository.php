<?php

namespace Foodics\Order\Repository;

use App\Models\User;
use App\Models\Order;

class OrderRepository
{

    public function createOrder(User $user): Order
    {
        $order = new Order;
        $order->user_id = $user->id;
        $order->status = Order::STATUS_PENDING;
        $order->save();

        return $order;
    }

    public function placeOrder(Order $order): Order
    {
        $order->status = Order::STATUS_PLACED;
        $order->save();

        return $order;
    }
}
