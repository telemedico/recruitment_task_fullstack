<?php

namespace App\PriceCalculator;

use App\PriceCalculator\DTO\PriceValue;

class Calculator implements CalculatorInterface
{
    /** @inheritDoc */
    public static function add(PriceValue $price, float $value): PriceValue
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Value must be positive");
        }

        $price->value = round($price->value + $value, 5);
        return $price;
    }

    /** @inheritDoc */
    public static function subtract(PriceValue $price, float $value): PriceValue
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Value must be positive");
        }

        $price->value = round($price->value - $value, 5);
        return $price;
    }
}
