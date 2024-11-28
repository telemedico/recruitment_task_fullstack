<?php

namespace App\Repository;

use App\Model\Currency;

class CurrencyRepositoryInMemory implements CurrencyRepositoryInterface
{

    public function findAll(): array
    {
        return [
            new Currency('EUR', 0.15, 0.05),
            new Currency('USD', 0.15, 0.05),
            new Currency('CZK', 0.15),
            new Currency('IDR', 0.15),
            new Currency('BRL', 0.15)
        ];
    }
}