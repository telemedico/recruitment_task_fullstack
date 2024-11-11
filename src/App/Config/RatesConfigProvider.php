<?php

declare(strict_types=1);

namespace App\Config;

/**
 * todo: This class should be moved to Symfony Configuration in the future, or even in the distant future - to Admin Panel
 */
class RatesConfigProvider
{
    private const BASE_CURRENCY = 'PLN';

    private const CURRENCIES_TO_NAMES = [
        'EUR' => 'Euro',
        'USD' => 'Dolar Amerykański',
        'CZK' => 'Korona Czeska',
        'IDR' => 'Rupia Indonezyjska',
        'BRL' => 'Real Brazylijski'
    ];

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
    public function getCurrencyCodes(): array
    {
        return array_keys(self::CURRENCIES_TO_NAMES);
    }

    /**
     * @return array<string, string> Keys - currency codes, Values - currency names
     */
    public function getCurrenciesAndNames(): array
    {
        return self::CURRENCIES_TO_NAMES;
    }

    public function getCurrencyName(string $code): ?string
    {
        return self::CURRENCIES_TO_NAMES[$code] ?? null;
    }

    /**
     * @return array[]
     */
    public function getRelativeRates(): array
    {
        return self::RELATIVE_RATES;
    }
}