<?php
namespace Foodics\Order\Command;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Foodics\Order\Repository\OrderRepository;
use Foodics\Order\Service\NewOrder\AddProductToOrder;

class CreateCommand
{

    public function __construct(
        private AddProductToOrder $addProductToOrder,
        private OrderRepository $orderRepository,
        private Product $productModel
    ) {}

    public function handle(array $orderProducts, User $user): Order
    {
        $order = $this->orderRepository->createOrder($user);
        foreach ($orderProducts as $orderProduct) {
            $product = $this->productModel->find($orderProduct['product_id']);
            /**
             * @note we can cut all Ingredient quantity in one operation but that will make code more complex
             */
            for ($i = 0; $i < $orderProduct['quantity']; $i++) {
                $this->addProductToOrder->execute($order, $product);
            }
        }

        return $this->orderRepository->placeOrder($order);
    }
}
