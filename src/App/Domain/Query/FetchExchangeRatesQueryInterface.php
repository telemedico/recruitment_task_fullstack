<?php

declare(strict_types=1);

namespace App\Domain\Query;

use App\Domain\Query\Filter\ExchangeRatesFilter;
use App\Domain\Query\View\ExchangeRatesView;

interface FetchExchangeRatesQueryInterface
{
    public function query(ExchangeRatesFilter $filter): ExchangeRatesView;
}
