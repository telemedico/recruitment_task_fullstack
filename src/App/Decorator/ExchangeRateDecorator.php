<?php
declare(strict_types=1);

namespace App\Decorator;

use App\NBPApi\DTO\ExchangesRatesDTO;
use App\PriceCalculator\Calculator;
use App\PriceCalculator\DTO\PriceValue;
use App\Utils\ArrayHelper;

class ExchangeRateDecorator
{
    public function addValuesToRates(ExchangesRatesDTO $exchangeRatesDTO, array $valuesToAdd): ExchangesRatesDTO
    {
        $rates = $exchangeRatesDTO->rates;
        foreach ($rates as $rate) {
            $priceValue = new PriceValue($rate->mid);
            if (in_array($rate->code, array_keys($valuesToAdd))) {
                $rate->buy = Calculator::subtract($priceValue, ArrayHelper::get($valuesToAdd, $rate->code . '.sub', 0))->value;
                $rate->sell = Calculator::add($priceValue, ArrayHelper::get($valuesToAdd, $rate->code . '.add', 0))->value;
            } else {
                $rate->buy = ArrayHelper::get($valuesToAdd, '*.sub');
                $rate->sell = Calculator::add($priceValue, ArrayHelper::get($valuesToAdd, '*.add', 0))->value;
            }
        }

        $exchangeRatesDTO->rates = $rates;
        return $exchangeRatesDTO;
    }
}
