<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRateDTO;
use DateTime;

class GetService implements GetServiceInterface
{
    /** {@inheritDoc} */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRateDTO
    {
        return new ExchangeRateDTO();
    }
}