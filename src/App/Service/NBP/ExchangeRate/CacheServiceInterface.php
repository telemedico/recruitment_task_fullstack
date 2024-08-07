<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

interface CacheServiceInterface
{
    /**
     * @param ExchangeRatesDTO $exchangeRatesDTO
     *
     * @return void
     */
    public function setCacheByExchangeRatesDTO(ExchangeRatesDTO $exchangeRatesDTO): void;

    /**
     * @param DateTime $date
     *
     * @return ExchangeRatesDTO|null
     */
    public function getCachedExchangeRatesDTOByDate(DateTime $date): ?ExchangeRatesDTO;
}