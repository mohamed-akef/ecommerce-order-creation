<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Foodics\Order\Command\CreateCommand;
use Foodics\Order\Repository\OrderRepository;
use Foodics\Order\Service\NewOrder\AddProductToOrder;
use PHPUnit\Framework\TestCase;

class CreateCommandTest extends TestCase
{

    public function testٍSuccessCreation()
    {
        $productId = 1;
        $quantity = 1;
        $mockAddProductToOrderService = $this->createMock(AddProductToOrder::class);
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockProductModel = $this->getMockBuilder(Product::class)
            ->addMethods(['find'])
            ->getMock();
        $mockUserModel = $this->createMock(User::class);
        $mockOrderModel = $this->createMock(Order::class);
        $createCommand = new CreateCommand($mockAddProductToOrderService, $mockOrderRepository, $mockProductModel);

        $mockOrderRepository
            ->expects(self::once())
            ->method('createOrder')
            ->with($mockUserModel)
            ->willReturn($mockOrderModel);

        $mockProductModel
            ->expects(self::once())
            ->method('find')
            ->willReturnSelf();

        $mockAddProductToOrderService
            ->expects(self::once())
            ->method('execute')
            ->with($mockOrderModel, $mockProductModel, $quantity);

        $mockOrderRepository
            ->expects(self::once())
            ->method('placeOrder')
            ->with($mockOrderModel)
            ->willReturn($mockOrderModel);

        $createCommand->handle(
            [
                [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]
            ],
            $mockUserModel
        );
    }

    public function testٍFailedCreationByNotFoundProduct()
    {
        $productId = 1;
        $quantity = 1;
        $mockAddProductToOrderService = $this->createMock(AddProductToOrder::class);
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockProductModel = $this->getMockBuilder(Product::class)
            ->addMethods(['find'])
            ->getMock();
        $mockUserModel = $this->createMock(User::class);
        $mockOrderModel = $this->createMock(Order::class);
        $createCommand = new CreateCommand($mockAddProductToOrderService, $mockOrderRepository, $mockProductModel);

        $mockOrderRepository
            ->expects(self::once())
            ->method('createOrder')
            ->with($mockUserModel)
            ->willReturn($mockOrderModel);

        $mockProductModel
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $createCommand->handle(
            [
                [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]
            ],
            $mockUserModel
        );
    }
}
