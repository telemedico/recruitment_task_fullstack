<?php

namespace App\Service\NBP\ExchangeRate\DTO;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

class FactoryService implements FactoryServiceInterface
{
    /** {@inheritDoc} */
    public function createExchangeRatesDTOByResponseDataAndDate(
        array    $responseData,
        DateTime $date
    ): ExchangeRatesDTO
    {
        return new ExchangeRatesDTO();
    }
}