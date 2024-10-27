<?php

namespace App\Service\ExchangeRates\Strategy;

interface ExchangeRateStrategy {
    public function calculateBuyRate(float $averageRate): ?float;
    public function calculateSellRate(float $averageRate): ?float;
}
