<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Dto\CurrenciesCollectionDto;
use App\Application\Query\GetExchangeRatesListQuery;

interface ExchangeRatesServiceInterface
{
    public function getList(GetExchangeRatesListQuery $query): CurrenciesCollectionDto;
}
