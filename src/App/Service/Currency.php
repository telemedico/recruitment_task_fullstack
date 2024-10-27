<?php

namespace App\Service;

class Currency {
    public const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'GBP', 'CZK', 'BRL'];

    public static function assertCurrencyCode(string $code): void {
        if (!array_key_exists($code, self::SUPPORTED_CURRENCIES)) {
            throw new \InvalidArgumentException("Unsupported currency code: {$code}");
        }
    }

    public static function getCurrencyName(string $code): string {
        switch ($code) {
            case 'USD':
                return 'Dolar amerykański';
            case 'EUR':
                return 'Euro';
            case 'GBP':
                return 'Funt brytyjski';
            case 'CZK':
                return 'Korona czeska';
            case 'BRL':
                return 'Real brazylijski';
            default:
                return 'Unknown';
        }
    }
}