<?php

namespace App\Service;

use App\Exception\InvalidPriceCommissionRateException;

class CurrencyPriceCalculator implements CurrencyPriceCalculatorInterface
{

    /**
     * @throws InvalidPriceCommissionRateException
     */
    public function calculateBuyPrice(float $baseValue, float $rate): float
    {
        $this->validateInput($baseValue, $rate);
        return round($baseValue - $rate, 2);
    }

    /**
     * @throws InvalidPriceCommissionRateException
     */
    public function calculateSellPrice(float $baseValue, float $rate): float
    {
        $this->validateInput($baseValue, $rate);
        return round($baseValue + $rate, 2);
    }

    /**
     * @throws InvalidPriceCommissionRateException
     */
    private function validateInput(float $baseValue, float $rate): void
    {
        if ($rate < 0 || $baseValue <= 0) {
            throw new InvalidPriceCommissionRateException(
                "Base value and rate should be positive"
            );
        }
    }
}