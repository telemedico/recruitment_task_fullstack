<?php

namespace App\Repository\API\NBP;

use DateTime;

interface ExchangeRateRepositoryInterface
{
    /**
     * @param DateTime $date
     * @param string $table
     *
     * @return array|null
     */
    public function getRatesByTableAndDate(DateTime $date, string $table = 'A'): ?array;
}