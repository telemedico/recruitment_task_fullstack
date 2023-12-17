<?php

declare(strict_types=1);

namespace App\Serializers;

use App\Dtos\Currency;

class CurrencySerializer
{
    public function toArray(Currency $currency): array
    {
        return [
            'currency' => $currency->getName(),
            'code' => $currency->getCode(),
            'price' => $currency->getPrice(),
            'buyPrice' => $currency->getBuyPrice(),
            'sellPrice' => $currency->getSellPrice(),
        ];
    }
}