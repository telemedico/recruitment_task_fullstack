<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

class CacheService implements CacheServiceInterface
{
    /** {@inheritDoc} */
    public function setCacheByExchangeRatesDTO(ExchangeRatesDTO $exchangeRatesDTO): void
    {
        // ToDo :: to implement
    }

    /** {@inheritDoc} */
    public function getCachedExchangeRatesDTOByDate(DateTime $date): ?ExchangeRatesDTO
    {
        return null;
    }
}