<?php

declare(strict_types=1);

namespace App\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;

class EurSpreadCalculator implements CurrencySpreadCalculatorInterface
{
    public function buyPrice(Currency $currency): ?float
    {
        $buyPrice = round($currency->getPrice() - 0.05, 4);

        return $buyPrice > 0 ? $buyPrice : null;
    }

    public function sellPrice(Currency $currency): ?float
    {
        return round($currency->getPrice() + 0.07, 4);
    }
}