<?php

namespace Unit\App\Service\NBP\ExchangeRate\DTO;

use App\DTO\NBP\ExchangeRatesDTO;
use App\Service\NBP\ExchangeRate\DTO\FactoryService;
use DateTime;
use Unit\TestCase;

class FactoryServiceTest extends TestCase
{
    public function testCreateExchangeRatesDTOByResponseDataAndDate()
    {
        $factoryServiceMock = $this->getMockedFactoryService();

        $result = $factoryServiceMock->createExchangeRatesDTOByResponseDataAndDate([], new DateTime());

        $this->assertInstanceOf(ExchangeRatesDTO::class, $result);
    }

    private function getMockedFactoryService(): FactoryService
    {
        return new FactoryService();
    }
}