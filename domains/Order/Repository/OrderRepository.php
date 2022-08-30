<?php

namespace Foodics\Order\Repository;

use App\Models\User;
use App\Models\Order;

class OrderRepository
{

    public function __construct(private Order $order)
    {}

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

    public function getFailedOrders()
    {
        $date = (new \DateTime())
            ->modify('-5 minutes');
        return $this->order
            ->where('status', 'pending')
            ->where('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->get();
    }
}
