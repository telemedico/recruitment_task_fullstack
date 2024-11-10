<?php

namespace App\RatesApi;

use DateTimeImmutable;

interface CurrencyRatesApiInterface
{
    /**
     * @param string[] $currencySymbols
     * @param DateTimeImmutable $date
     * @return array
     */
    public function get(array $currencySymbols, DateTimeImmutable $date): array;
}