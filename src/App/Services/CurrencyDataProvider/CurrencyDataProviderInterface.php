<?php

declare(strict_types=1);

namespace App\Services\CurrencyDataProvider;

use App\Dtos\CurrencyCollection;
use DateTime;

interface CurrencyDataProviderInterface
{
    public function getData(DateTime $date): CurrencyCollection;
}