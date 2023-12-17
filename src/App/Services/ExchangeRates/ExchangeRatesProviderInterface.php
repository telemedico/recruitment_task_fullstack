<?php

declare(strict_types=1);

namespace App\Services\ExchangeRates;

use App\Dtos\CurrencyCollection;
use DateTime;

interface ExchangeRatesProviderInterface
{
    public function getExchangeRates(DateTime $date): CurrencyCollection;
}