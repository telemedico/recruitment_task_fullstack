<?php

declare(strict_types = 1);

namespace App\ExchangeRate;

class UsedCurrenciesProvider
{
    /**
     * @var string[]
     */
    private $usedCurrencies;

    public function __construct(
        array $usedCurrencies = []
    ) {
        $this->usedCurrencies = $usedCurrencies;
    }

    /**
     * @return string[]
     */
    public function getCurrencies(): array
    {
        return $this->usedCurrencies;
    }
}