<?php

namespace App\Repository\API\NBP;

use DateTime;

interface ExchangeRateRepositoryInterface
{
    const ENDPOINT_DEFAULT_TABLE_CODE = 'A';
    const EXCHANGE_RATES_ENDPOINT_PATTERN = 'https://api.nbp.pl/api/exchangerates/tables/%s/%s/?format=json';

    /**
     * @param DateTime $date
     * @param string $table
     *
     * @return array|null
     */
    public function getRatesByTableAndDate(DateTime $date, string $table = 'A'): ?array;
}