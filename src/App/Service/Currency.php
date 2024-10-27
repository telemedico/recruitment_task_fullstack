<?php

namespace App\Service;

class Currency {
    public const SUPPORTED_CURRENCIES = [
        'EUR' => 'Euro',
        'USD' => 'Dolar amerykański',
        'CZK' => 'Korona czeska',
        'IDR' => 'Rupia indonezyjska',
        'BRL' => 'Real brazylijski',
    ];

    public static function assertCurrencyCode(string $code): void {
        if (!array_key_exists($code, self::SUPPORTED_CURRENCIES)) {
            throw new \InvalidArgumentException("Unsupported currency code: {$code}");
        }
    }

    public static function getCurrencyName(string $code): string {
        return self::SUPPORTED_CURRENCIES[$code] ?? 'Unknown';
    }
}