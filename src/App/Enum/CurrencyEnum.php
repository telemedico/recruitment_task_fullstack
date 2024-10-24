<?php

declare(strict_types=1);

namespace App\Enum;

class CurrencyEnum
{
    public const PLN = 'PLN';
    public const EUR = 'EUR';
    public const USD = 'USD';
    public const CZK = 'CZK';
    public const IDR = 'IDR';
    public const BRL = 'BRL';

    public static function supportedCurrencies(): array
    {
        return [
            self::EUR,
            self::USD,
            self::CZK,
            self::IDR,
            self::BRL,
        ];
    }

    public static function supports(string $currency): bool
    {
        return in_array($currency, self::supportedCurrencies(), true);
    }
}