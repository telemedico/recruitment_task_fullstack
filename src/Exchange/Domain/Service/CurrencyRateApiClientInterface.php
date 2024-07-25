<?php
namespace App\Exchange\Domain\Service;

use App\Exchange\Infrastructure\Http\ApiCurrencyRate;

interface CurrencyRateApiClientInterface
{

    /**
     * Get exchange rates for a specific date.
     *
     * @param string $currency
     * @param string $date
     * @return ApiCurrencyRate
     */
    public function getExchangeRate(string $currency, string $date): object;
}
