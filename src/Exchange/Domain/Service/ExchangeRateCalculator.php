<?php
namespace App\Exchange\Domain\Service;

class ExchangeRateCalculator
{
    public function calculateBuyRate(string $currency, float $nbpRate): ?float
    {
        if (in_array($currency, ['EUR', 'USD'])) {
            return $nbpRate - 0.05;
        }
        return null;
    }

    public function calculateSellRate(string $currency, float $nbpRate): float
    {
        if (in_array($currency, ['EUR', 'USD'])) {
            return $nbpRate + 0.07;
        }
        return $nbpRate + 0.15;
    }
}
