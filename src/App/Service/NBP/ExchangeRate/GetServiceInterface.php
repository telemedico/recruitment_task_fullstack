<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface GetServiceInterface
{
    /**
     * @param DateTime $date
     *
     * @return ExchangeRatesDTO
     *
     * @throws NotFoundHttpException
     */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRatesDTO;
}