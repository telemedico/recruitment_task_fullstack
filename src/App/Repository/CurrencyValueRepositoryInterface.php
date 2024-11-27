<?php

namespace App\Repository;

use App\Model\CurrencyValue;
use DateTime;

interface CurrencyValueRepositoryInterface
{
    public function findByCurrencyCodeAndDate(string $code, DateTime $date): CurrencyValue;
}