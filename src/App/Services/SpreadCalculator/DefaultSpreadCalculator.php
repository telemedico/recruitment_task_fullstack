<?php

declare(strict_types=1);

namespace App\Services\SpreadCalculator;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\Currencies\CurrencySpreadCalculatorInterface;
use App\Services\SpreadCalculator\Currencies\DefaultCurrencySpreadCalculator;
use App\Services\SpreadCalculator\Currencies\EurSpreadCalculator;
use App\Services\SpreadCalculator\Currencies\UsdSpreadCalculator;

class DefaultSpreadCalculator implements SpreadCalculatorInterface
{
    public function buyPrice(Currency $currency): ?float
    {
        $calculator = $this->getCurrencySpreadCalculator($currency);

        return $calculator->buyPrice($currency);
    }

    public function sellPrice(Currency $currency): ?float
    {
        $calculator = $this->getCurrencySpreadCalculator($currency);

        return $calculator->sellPrice($currency);
    }

    private function getCurrencySpreadCalculator(Currency $currency): CurrencySpreadCalculatorInterface
    {
        switch (strtoupper($currency->getCode())) {
            case 'USD':
                return new UsdSpreadCalculator();
            case 'EUR':
                return new EurSpreadCalculator();
            default:
                return new DefaultCurrencySpreadCalculator();
        }
    }
}