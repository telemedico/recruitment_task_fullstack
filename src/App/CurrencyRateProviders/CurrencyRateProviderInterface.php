<?php

declare(strict_types=1);

namespace App\CurrencyRateProviders;

use App\Dto\CurrencyRatesDto;
use DateTime;

interface CurrencyRateProviderInterface
{
    public function getCurrencyRates(?DateTime $dateTime = null): CurrencyRatesDto;
}