<?php

namespace App\Repository;

use App\Model\Currency;

interface CurrencyRepositoryInterface
{
    /** @return Currency[] */
    public function findAll(): array;
}