<?php

namespace App\Service\ExchangeRates\Strategy;

class OtherCurrencyExchangeRateStrategy implements ExchangeRateStrategy {
    private const SELL_MARGIN = 0.15;

    public function calculateBuyRate(float $averageRate): ?float {
        return null;
    }

    public function calculateSellRate(float $averageRate): float {
        return round($averageRate + self::SELL_MARGIN, 4);
    }
}