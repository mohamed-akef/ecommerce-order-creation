<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{

    public function testSuccessDeductStock(): void
    {
        $currentStock = 5000;
        $deduct = 1000;
        $expectedNewStock = $currentStock - $deduct;

        $ingredient = new Ingredient();
        $ingredient->current_quantity = $currentStock;
        $ingredient->deduct($deduct);

        $this->assertEquals($expectedNewStock, $ingredient->current_quantity);
    }

    public function testFailedDeductStock(): void
    {
        $currentStock = 5000;
        $deduct = 6000;

        $ingredient = new Ingredient();
        $ingredient->current_quantity = $currentStock;

        $this->expectException(\RuntimeException::class);
        $ingredient->deduct($deduct);
    }

    public function testSuccessIncreaseStock(): void
    {
        $currentStock = 5000;
        $increase = 1000;
        $expectedNewStock = $currentStock + $increase;

        $ingredient = new Ingredient();
        $ingredient->current_quantity = $currentStock;
        $ingredient->increase($increase);

        $this->assertEquals($expectedNewStock, $ingredient->current_quantity);
    }
}
