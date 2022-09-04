<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductIngredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Foodics\Order\Repository\OrderProductIngredientRepository;
use Foodics\Order\Repository\OrderProductRepository;
use Foodics\Order\Service\NewOrder\AddProductToOrder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AddProductToOrderTest extends TestCase
{

    public function testSuccessAdding(): void
    {
        $quantity = 1;

        $mockOrderProductRepo = $this->createMock(OrderProductRepository::class);
        $mockOrderProductModel = $this->createMock(OrderProduct::class);
        $mockOrderProductIngredientRepo = $this->createMock(OrderProductIngredientRepository::class);
        $mockOrder = $this->createMock(Order::class);
        $mockProductModel = $this->createMock(Product::class);
        $mockProductIngredient = $this->getMockBuilder(ProductIngredient::class)
            ->addMethods(['get', 'where'])
            ->getMock();
        $mockIngredientModel = $this->getMockBuilder(Ingredient::class)
            ->addMethods(['find'])
            ->getMock();

        $mockIngredient = $this->buildIngredient();
        $productIngredient = $this->buildProductIngredient();

        $deductQuantity = ($productIngredient->quantity * $quantity);

        $addProductToOrderService = new AddProductToOrder(
            $mockOrderProductRepo,
            $mockOrderProductIngredientRepo,
            $mockProductIngredient,
            $mockIngredientModel
        );

        $mockOrderProductRepo
            ->expects(self::once())
            ->method('createOrderProduct')
            ->with($mockOrder, $mockProductModel, $quantity)
            ->willReturn($mockOrderProductModel);

        $mockProductIngredient
            ->expects(self::once())
            ->method('where')
            ->willReturnSelf();
        $mockProductIngredient
            ->expects(self::once())
            ->method('get')
            ->willReturn([$productIngredient]);

        DB::shouldReceive('beginTransaction')
            ->once();

        $mockIngredientModel
            ->expects(self::once())
            ->method('find')
            ->willReturn($mockIngredient);

        $mockOrderProductIngredientRepo
            ->expects(self::once())
            ->method('createNew')
            ->with($mockOrderProductModel, $deductQuantity, OrderProductIngredient::STATUS_RESERVED);

        DB::shouldReceive('commit')
            ->once();

        $addProductToOrderService->execute($mockOrder, $mockProductModel, $quantity);

        $this->assertEquals(
            $mockIngredient->init_quantity - $deductQuantity,
            $mockIngredient->current_quantity
        );
    }

    public function testFailedAddingWithByStockLimit(): void
    {
        $quantity = 200;

        $mockOrderProductRepo = $this->createMock(OrderProductRepository::class);
        $mockOrderProductModel = $this->createMock(OrderProduct::class);
        $mockOrderProductIngredientRepo = $this->createMock(OrderProductIngredientRepository::class);
        $mockOrder = $this->createMock(Order::class);
        $mockProductModel = $this->createMock(Product::class);
        $mockProductIngredient = $this->getMockBuilder(ProductIngredient::class)
            ->addMethods(['get', 'where'])
            ->getMock();
        $mockIngredientModel = $this->getMockBuilder(Ingredient::class)
            ->addMethods(['find'])
            ->getMock();

        $mockIngredient = $this->buildIngredient();
        $productIngredient = $this->buildProductIngredient();

        $deductQuantity = ($productIngredient->quantity * $quantity);

        $addProductToOrderService = new AddProductToOrder(
            $mockOrderProductRepo,
            $mockOrderProductIngredientRepo,
            $mockProductIngredient,
            $mockIngredientModel
        );

        $mockOrderProductRepo
            ->expects(self::once())
            ->method('createOrderProduct')
            ->with($mockOrder, $mockProductModel, $quantity)
            ->willReturn($mockOrderProductModel);

        $mockProductIngredient
            ->expects(self::once())
            ->method('where')
            ->willReturnSelf();
        $mockProductIngredient
            ->expects(self::once())
            ->method('get')
            ->willReturn([$productIngredient]);

        DB::shouldReceive('beginTransaction');

        $mockIngredientModel
            ->expects(self::atLeast(1))
            ->method('find')
            ->willReturn($mockIngredient);

        $mockOrderProductIngredientRepo
            ->expects(self::atLeast(1))
            ->method('createNew')
            ->with($mockOrderProductModel, $deductQuantity, OrderProductIngredient::STATUS_RESERVED);

        DB::shouldReceive('commit');
        DB::shouldReceive('rollBack')
            ->once();

        $this->expectException(\RuntimeException::class);

        $addProductToOrderService->execute($mockOrder, $mockProductModel, $quantity);

    }

    private function buildProductIngredient(): ProductIngredient
    {
        $productIngredient = new ProductIngredient();
        $productIngredient->product_id = 1;
        $productIngredient->ingredient = 1;
        $productIngredient->quantity = 1000;

        return $productIngredient;
    }

    private function buildIngredient()
    {
        $ingredient = $this->createPartialMock(Ingredient::class, ['save']);

        $ingredient->name = 'test';
        $ingredient->init_quantity = 5000;
        $ingredient->current_quantity = 5000;

        return $ingredient;
    }
}
