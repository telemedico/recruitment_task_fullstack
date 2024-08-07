<?php

namespace App\Repository\API\NBP;

use DateTime;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    /** {@inheritDoc} */
    public function getRatesByTableAndDate(DateTime $date, string $table = 'A'): ?array
    {
        return null;
    }
}