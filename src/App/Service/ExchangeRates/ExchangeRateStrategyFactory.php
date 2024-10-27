<?php

namespace App\Service\ExchangeRates;

use App\Service\ExchangeRates\Strategy\EurUsdExchangeRateStrategy;
use App\Service\ExchangeRates\Strategy\ExchangeRateStrategy;
use App\Service\ExchangeRates\Strategy\OtherCurrencyExchangeRateStrategy;

class ExchangeRateStrategyFactory {
    public static function getStrategy(string $currency): ExchangeRateStrategy {
        switch ($currency) {
            case 'EUR':
            case 'USD':
                return new EurUsdExchangeRateStrategy();
            default:
                return new OtherCurrencyExchangeRateStrategy();
        }
    }
}
