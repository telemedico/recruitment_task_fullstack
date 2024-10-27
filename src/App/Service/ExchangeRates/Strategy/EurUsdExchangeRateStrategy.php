<?php

namespace App\Service\ExchangeRates\Strategy;

class EurUsdExchangeRateStrategy implements ExchangeRateStrategy {

    private const BUY_MARGIN = 0.05;
    private const SELL_MARGIN = 0.07;
    public function calculateBuyRate(float $averageRate): float {
        return (float) number_format($averageRate - self::BUY_MARGIN, 4);
    }

    public function calculateSellRate(float $averageRate): float {
        return (float) number_format($averageRate + self::SELL_MARGIN, 4);
    }
}
