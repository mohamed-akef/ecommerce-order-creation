<?php

namespace Foodics\Order\Service\QuantityCalculator;

class WeightCalculator implements CalculatorInterface
{

    public function deduct(int $from, int $deduct): int
    {
        $newStock = $from - $deduct;
        if ($newStock < 0) {
            throw new \RuntimeException('The current stock not enough');
        }

        return $newStock;
    }

    public function increase(int $increase, int $into): int
    {
        return $into + $increase;
    }
}
