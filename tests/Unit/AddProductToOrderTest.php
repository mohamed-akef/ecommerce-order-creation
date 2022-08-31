<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductIngredient;
use Foodics\Order\Repository\OrderProductIngredientRepository;
use Foodics\Order\Repository\OrderProductRepository;
use Foodics\Order\Service\NewOrder\AddProductToOrder;
use Tests\TestCase;

class AddProductToOrderTest extends TestCase
{

    public function testSuccessAdding(): void
    {
        $mockOrderProductRepo = $this->createMock(OrderProductRepository::class);
        $mockOrderProductIngredientRepo = $this->createMock(OrderProductIngredientRepository::class);
        $mockProductIngredient = $this->getMockBuilder(ProductIngredient::class)
            ->addMethods(['get', 'where'])
            ->getMock();
        $mockIngredientModel = $this->getMockBuilder(Ingredient::class)
            ->addMethods(['find'])
            ->getMock();
        $mockIngredient = $this->buildIngredient();
        $mockIngredientModel
            ->expects(self::once())
            ->method('find')
            ->willReturn($mockIngredient);

        $productIngredient = $this->buildProductIngredient();
        $mockProductIngredient
            ->expects(self::once())
            ->method('where')
            ->willReturnSelf();
        $mockProductIngredient
            ->expects(self::once())
            ->method('get')
            ->willReturn([$productIngredient]);

        $addProductToOrderService = new AddProductToOrder(
            $mockOrderProductRepo,
            $mockOrderProductIngredientRepo,
            $mockProductIngredient,
            $mockIngredientModel
        );

        $mockOrder = $this->createMock(Order::class);
        $mockProductModel = $this->createMock(Product::class);
        $quantity = 1;

        $mockOrderProductIngredientRepo
            ->expects(self::once())
            ->method('createNew');


        $addProductToOrderService->execute($mockOrder, $mockProductModel, $quantity);

        $this->assertEquals(
            $mockIngredient->init_quantity - ($productIngredient->quantity * $quantity),
            $mockIngredient->current_quantity
        );
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
