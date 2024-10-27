<?php

namespace App\Service\ExchangeRates\Strategy;

class OtherCurrencyExchangeRateStrategy implements ExchangeRateStrategy {
    public function calculateBuyRate(float $averageRate): ?float {
        return null;
    }

    public function calculateSellRate(float $averageRate): float {
        return $averageRate + 0.15;
    }
}
