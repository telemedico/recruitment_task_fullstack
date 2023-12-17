<?php

declare(strict_types=1);

namespace App\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;

interface CurrencySpreadCalculatorInterface
{
    public function buyPrice(Currency $currency): ?float;

    public function sellPrice(Currency $currency): ?float;
}