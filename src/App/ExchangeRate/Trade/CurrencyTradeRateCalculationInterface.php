<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Trade;

use App\ExchangeRate\DTO\ExchangeRateInterface;

interface CurrencyTradeRateCalculationInterface
{
    public function calculate(ExchangeRateInterface $rate): ?float;
}