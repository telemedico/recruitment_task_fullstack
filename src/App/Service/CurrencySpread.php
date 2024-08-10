<?php

declare(strict_types=1);

namespace App\Service;

class CurrencySpread
{
    const BUY_SPREAD = 'buySpread';
    const SELL_SPREAD = 'sellSpread';
    /**
     * @var array<mixed>
     */
    private $currencySpreads;

    public function __construct(array $currencySpread)
    {
        $this->currencySpreads = $currencySpread;
    }

    public function calculateBuyPrice(string $currencyCode, string $price): ?string
    {
        if (!isset($this->currencySpreads[$currencyCode][self::BUY_SPREAD])) {
            return null;
        }
        return (string)(floatval($price) - floatval($this->currencySpreads[$currencyCode][self::BUY_SPREAD]));
    }

    public function calculateSellPrice(string $currencyCode, string $price): ?string
    {
        if (!isset($this->currencySpreads[$currencyCode][self::SELL_SPREAD])) {
            return null;
        }
        return (string)(floatval($price) + floatval($this->currencySpreads[$currencyCode][self::SELL_SPREAD]));
    }

    public function supportedCurrencies(): array
    {
        return array_keys($this->currencySpreads);
    }
}
