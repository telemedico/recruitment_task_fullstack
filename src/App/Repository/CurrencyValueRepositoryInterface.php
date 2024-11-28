<?php

namespace App\Repository;

use App\Exception\CurrencyValueNotFoundException;
use App\Model\CurrencyValue;
use DateTime;

interface CurrencyValueRepositoryInterface
{
    /** @throws CurrencyValueNotFoundException */
    public function findByCurrencyCodeAndDate(string $code, DateTime $date): CurrencyValue;
}