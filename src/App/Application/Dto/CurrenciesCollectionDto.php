<?php

declare(strict_types=1);

namespace App\Application\Dto;

final class CurrenciesCollectionDto
{
    /**
     * @var CurrencyDto[]
     */
    private $currencies;

    public function __construct(CurrencyDto ...$currencies)
    {
        $this->currencies = $currencies;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }
}
