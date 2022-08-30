<?php

namespace Foodics\Order\Service\QuantityCalculator;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CalculatorFactory
{

    public function getCalculator(string $unitType) :CalculatorInterface
    {
        return match ($unitType) {
            'weight' => new WeightCalculator(),
            default => throw new BadRequestException(),
        };
    }
}
