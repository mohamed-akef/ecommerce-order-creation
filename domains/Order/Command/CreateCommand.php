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

    public function handle(array $productsData, User $user): Order
    {
        $order = $this->orderRepository->createOrder($user);
        foreach ($productsData as $productData) {
            $product = $this->productModel->find($productData['product_id']);
            if ($product === null) {
                throw new \RuntimeException('Product not Found');
            }
            $this->addProductToOrder->execute($order, $product, $productData['quantity']);
        }

        return $this->orderRepository->placeOrder($order);
    }
}
