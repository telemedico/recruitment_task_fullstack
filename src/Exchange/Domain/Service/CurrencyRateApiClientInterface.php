<?php

declare(strict_types=1);

namespace App\Exchange\Domain\Service;

use App\Exchange\Infrastructure\Http\ApiCurrencyRate;

interface CurrencyRateApiClientInterface
{
    /**
     * Get exchange rates for a specific date.
     *
     * @return ApiCurrencyRate
     */
    public function getExchangeRate(string $currency, \DateTimeImmutable $date): object;
}
