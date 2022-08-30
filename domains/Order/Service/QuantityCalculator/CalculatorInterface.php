<?php

namespace Foodics\Order\Service\QuantityCalculator;

interface CalculatorInterface
{
    public function deduct(int $from, int $deduct): int;

    public function increase(int $increase, int $into): int;
}
