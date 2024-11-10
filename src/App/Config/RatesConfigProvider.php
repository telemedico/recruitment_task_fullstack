<?php

declare(strict_types=1);

namespace App\Config;

class RatesConfigProvider
{
    private const BASE_CURRENCY = 'PLN';

    private const CURRENCIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

    private const RELATIVE_RATES = [
        [
            'currencies' => ['EUR', 'USD'],
            'buy' => -0.05,
            'sell' => 0.07,
        ],
        [
            'currencies' => ['CZK', 'IDR', 'BRL'],
            'buy' => null,
            'sell' => 0.15,
        ],
    ];

    public function getBaseCurrency(): string
    {
        return self::BASE_CURRENCY;
    }

    /**
     * @return string[]
     */
    public function getCurrencies(): array
    {
        return self::CURRENCIES;
    }

    /**
     * @return array[]
     */
    public function getRelativeRates(): array
    {
        return self::RELATIVE_RATES;
    }
}