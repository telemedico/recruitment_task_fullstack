<?php

namespace App\Service;

use App\ViewModel\CurrencyPriceViewModel;
use DateTime;

interface CurrencyPriceViewServiceInterface
{
    /** @return CurrencyPriceViewModel[] */
    public function getAllCurrencyPricesByDate(DateTime $date): array;
}