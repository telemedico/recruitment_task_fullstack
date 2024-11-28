<?php

namespace App\Service;

interface CurrencyPriceCalculatorInterface
{
    public function calculateBuyPrice(float $baseValue, float $rate): float;
    public function calculateSellPrice(float $baseValue, float $rate): float;
}