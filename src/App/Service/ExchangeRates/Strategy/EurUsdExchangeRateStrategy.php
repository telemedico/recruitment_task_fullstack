<?php

namespace App\Service\ExchangeRates\Strategy;

class EurUsdExchangeRateStrategy implements ExchangeRateStrategy {
    public function calculateBuyRate(float $averageRate): float {
        return $averageRate - 0.05;
    }

    public function calculateSellRate(float $averageRate): float {
        return $averageRate + 0.07;
    }
}
