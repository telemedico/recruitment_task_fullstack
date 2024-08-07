<?php

namespace Unit\App\Repository\API\NBP;

use App\Repository\API\NBP\ExchangeRateRepository;
use DateTime;
use Unit\TestCase;

class ExchangeRateRepositoryTest extends TestCase
{
    public function testGetRatesByTableAndDateWhenIsError(): void
    {
        $exchangeRateRepositoryMock = $this->getMockedExchangeRateRepository();

        $result = $exchangeRateRepositoryMock->getRatesByTableAndDate(new DateTime());

        $this->assertNull($result);
    }

    public function testGetRatesByTableAndDateWhenSuccess(): void
    {
        $exchangeRateRepositoryMock = $this->getMockedExchangeRateRepository();

        $result = $exchangeRateRepositoryMock->getRatesByTableAndDate(new DateTime());

        $this->assertIsArray($result);
    }

    private function getMockedExchangeRateRepository(): ExchangeRateRepository
    {
        return new ExchangeRateRepository();
    }
}