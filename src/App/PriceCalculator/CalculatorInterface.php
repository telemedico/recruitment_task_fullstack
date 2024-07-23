<?php
declare(strict_types=1);

namespace App\PriceCalculator;

use App\PriceCalculator\DTO\PriceValue;

interface CalculatorInterface
{
    /**
     * @param PriceValue $price
     * @param float $value
     * @return PriceValue
     */
    public static function add(PriceValue $price, float $value): PriceValue;

    /**
     * @param PriceValue $price
     * @param float $value
     * @return PriceValue
     */
    public static function subtract(PriceValue $price, float $value): PriceValue;

}
