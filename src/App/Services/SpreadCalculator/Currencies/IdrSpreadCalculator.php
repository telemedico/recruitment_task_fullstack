<?php

declare(strict_types=1);

namespace App\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;

class IdrSpreadCalculator implements CurrencySpreadCalculatorInterface
{
    public function buyPrice(Currency $currency): ?float
    {
        return null;
    }

    public function sellPrice(Currency $currency): ?float
    {
        return round($currency->getPrice() + 0.15, 8);
    }
}
