<?php

declare(strict_types=1);

namespace App\Serializers;

use App\Dtos\CurrencyCollection;

class CurrencyCollectionSerializer
{
    private $currencySerializer;

    public function __construct(CurrencySerializer $currencySerializer)
    {
        $this->currencySerializer = $currencySerializer;
    }

    public function toArray(CurrencyCollection $currencyCollection): array
    {
        $result = [];

        foreach ($currencyCollection as $currency) {
            $result[] = $this->currencySerializer->toArray($currency);
        }

        return $result;
    }
}