<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Trade\Strategies;

use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\Trade\CurrencyTradeRateCalculationInterface;

class SellingStrategyOne implements CurrencyTradeRateCalculationInterface
{
    public function calculate(ExchangeRateInterface $rate): ?float
    {
        return round($rate->getRate() + 0.07, 4);
    }
}