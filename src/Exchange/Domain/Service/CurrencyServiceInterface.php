<?php

namespace App\Exchange\Domain\Service;

use App\Exchange\Domain\Model\CurrencyRate;

interface CurrencyServiceInterface
{
    /**
     * Get exchange rates for a specific date.
     *
     * @param string $date
     * @return CurrencyRate[]
     */
    public function getExchangeRates(string $date): array;
}