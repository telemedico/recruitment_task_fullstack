<?php

declare(strict_types = 1);

namespace App\ExchangeRate;

use App\ExchangeRate\DTO\ExchangeRateInterface;

interface ExchangeRatesRequestDataModifierInterface
{
    public function modify(ExchangeRateInterface $rate): ExchangeRateInterface;

    /**
     * @param ExchangeRateInterface[] $rates
     * @return ExchangeRateInterface[]
     */
    function modifyMany(array $rates): array;
}