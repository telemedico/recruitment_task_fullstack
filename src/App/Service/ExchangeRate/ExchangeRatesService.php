<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\Service\ExchangeRate\Strategy\EurUsdRateStrategy;
use App\Service\ExchangeRate\Strategy\DefaultRateStrategy;
use App\Interfaces\ExchangeRate\RateStrategy;

class ExchangeRatesService {
    private $strategies;

    public function __construct() {
        $this->strategies = [
            'EUR' => new EurUsdRateStrategy(),
            'USD' => new EurUsdRateStrategy(),
        ];
    }

    public function getStrategy(string $code): RateStrategy {
        return $this->strategies[$code] ?? new DefaultRateStrategy();
    }

    public function processExchangeRates(array $ratesData): array {
        $processedRates = [];

        foreach ($ratesData as $rate) {
            $strategy = $this->getStrategy($rate['code']);
            $exchangeRate = new ExchangeRate($rate['currency'], $rate['code'], round($rate['mid'], 8), $strategy);

            $processedRates[] = [
                'currency' => $exchangeRate->getCurrency(),
                'code' => $exchangeRate->getCode(),
                'mid' => $exchangeRate->getMid(),
                'buy' => $exchangeRate->getBuyRate(),
                'sell' => $exchangeRate->getSellRate()
            ];
        }

        return $processedRates;
    }
}
