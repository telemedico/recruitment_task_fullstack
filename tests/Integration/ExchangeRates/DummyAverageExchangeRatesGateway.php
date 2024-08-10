<?php

declare(strict_types=1);

namespace Integration\ExchangeRates;

use App\Gateway\AverageExchangeRateDto;
use App\Gateway\AverageExchangeRatesGateway;
use DateTimeImmutable;

final class DummyAverageExchangeRatesGateway implements AverageExchangeRatesGateway
{
    /**
     * @return AverageExchangeRateDto[]
     */
    public function fetchRates(DateTimeImmutable $dateTime, string ...$currenciesCodes): array
    {
        return [
            new AverageExchangeRateDto('dolar amerykański', 'USD', '3.9331'),
            new AverageExchangeRateDto('euro', 'EUR', '4.3073')
        ];
    }
}
