<?php
namespace Foodics\Order\Handler;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Foodics\Order\Service\AddProductToOrder;
use Foodics\Order\Service\FinishOrder;
use Foodics\Order\Service\StartOrder;

class CreateHandler
{

    public function __construct(
        private AddProductToOrder $addProductToOrder,
        private StartOrder $startOrder,
        private FinishOrder $finishOrder
    ) {}

    public function handle(array $orderProducts): Order
    {
        $user = User::find(1);

        $order = $this->startOrder->execute($user);

        foreach ($orderProducts as $orderProduct){
            $product = Product::find($orderProduct['product_id']);
            $this->addProductToOrder->execute($order, $product, $orderProduct['quantity']);
        }

        return $this->finishOrder->execute($order);
    }
}
