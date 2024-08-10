<?php

declare(strict_types=1);

namespace App\Gateway;

use DateTimeImmutable;

interface AverageExchangeRatesGateway
{
    /**
     * @return array<AverageExchangeRateDto>
     */
    public function fetchRates(DateTimeImmutable $dateTime, string ...$currenciesCodes): array;
}
