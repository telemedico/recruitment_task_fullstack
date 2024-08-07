<?php

namespace Unit\App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use App\Exception\NBPException;
use App\Service\NBP\ExchangeRate\GetService;
use DateTime;
use Unit\TestCase;

class GetServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetExchangeRateDTOByDateWhenThrowError(): void
    {
        $getServiceMock = $this->getMockedGetService();

        $this->expectException(NBPException::class);

        $getServiceMock->getExchangeRateDTOByDate(new DateTime());
    }

    public function testGetExchangeRateDTOByDateWhenIsSuccess(): void
    {
        $getServiceMock = $this->getMockedGetService();

        $result = $getServiceMock->getExchangeRateDTOByDate(new DateTime());

        $this->assertInstanceOf(ExchangeRatesDTO::class, $result);
    }

    private function getMockedGetService(): GetService
    {
        return new GetService();
    }
}