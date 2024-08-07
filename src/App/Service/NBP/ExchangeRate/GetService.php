<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

class GetService implements GetServiceInterface
{
    /** {@inheritDoc} */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRatesDTO
    {
        return new ExchangeRatesDTO();
    }
}