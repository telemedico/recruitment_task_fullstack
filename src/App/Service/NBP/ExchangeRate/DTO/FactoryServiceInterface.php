<?php

namespace App\Service\NBP\ExchangeRate\DTO;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;

interface FactoryServiceInterface
{
    /**
     * @param array $responseData
     * @param DateTime $date
     *
     * @return ExchangeRatesDTO
     */
    public function createExchangeRatesDTOByResponseDataAndDate(
        array    $responseData,
        DateTime $date
    ): ExchangeRatesDTO;
}