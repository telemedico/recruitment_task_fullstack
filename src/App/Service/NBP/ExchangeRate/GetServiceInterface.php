<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRateDTO;
use DateTime;

interface GetServiceInterface
{
    /**
     * @param DateTime $date
     *
     * @return ExchangeRateDTO
     */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRateDTO;
}