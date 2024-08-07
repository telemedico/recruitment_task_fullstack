<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

interface GetServiceInterface
{
    /**
     * @param DateTime $date
     *
     * @return ExchangeRatesDTO
     */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRatesDTO;
}