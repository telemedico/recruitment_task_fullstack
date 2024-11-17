<?php

declare(strict_types=1);

namespace App\Application\Config;

final class ConfigProvider implements ConfigProviderInterface
{
    private const CURRENCIES_CONFIG = [
        'EUR' => [
            'bid' => [
                'available' => true,
                'shift' => 0.05,
            ],
            'ask' => [
                'available' => true,
                'shift' => 0.07,
            ],
        ],
        'USD' => [
            'bid' => [
                'available' => true,
                'shift' => 0.05,
            ],
            'ask' => [
                'available' => true,
                'shift' => 0.07,
            ],
        ],
        'CZK' => [
            'bid' => [
                'available' => false,
                'shift' => 0.00,
            ],
            'ask' => [
                'available' => true,
                'shift' => 0.15,
            ],
        ],
        'IDR' => [
            'bid' => [
                'available' => false,
                'shift' => 0.00,
            ],
            'ask' => [
                'available' => true,
                'shift' => 0.15,
            ],
        ],
        'BRL' => [
            'bid' => [
                'available' => false,
                'shift' => 0.00,
            ],
            'ask' => [
                'available' => true,
                'shift' => 0.15,
            ],
        ],
    ];

    public function getAvailableCurrencies(): array
    {
        return \array_keys(self::CURRENCIES_CONFIG);
    }

    public function getBidShiftForCurrency(string $currency): float
    {
        if (isset(self::CURRENCIES_CONFIG[$currency]['bid']['shift'])) {
            return self::CURRENCIES_CONFIG[$currency]['bid']['shift'];
        }

        throw new \UnexpectedValueException('Unexpected currency.');
    }

    public function getAskShiftForCurrency(string $currency): float
    {
        if (isset(self::CURRENCIES_CONFIG[$currency]['ask']['shift'])) {
            return self::CURRENCIES_CONFIG[$currency]['ask']['shift'];
        }

        throw new \UnexpectedValueException('Unexpected currency.');
    }

    public function isBidAvailableForCurrency(string $currency): bool
    {
        return self::CURRENCIES_CONFIG[$currency]['bid']['available'] ?? false;
    }

    public function isAskAvailableForCurrency(string $currency): bool
    {
        return self::CURRENCIES_CONFIG[$currency]['ask']['available'] ?? false;
    }
}
