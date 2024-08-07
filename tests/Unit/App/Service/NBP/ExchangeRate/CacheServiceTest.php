<?php

namespace Unit\App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use App\Service\NBP\ExchangeRate\CacheService;
use DateTime;
use Unit\TestCase;

class CacheServiceTest extends TestCase
{
    public function testSetCacheByExchangeRatesDTO(): void
    {
        $cacheServiceMock = $this->getMockedCacheService();

        $this->assertNull(
            $cacheServiceMock->setCacheByExchangeRatesDTO(new ExchangeRatesDTO())
        );
    }

    public function testGetCachedExchangeRatesDTOByDateWhenDataExists(): void
    {
        $cacheServiceMock = $this->getMockedCacheService();

        $result = $cacheServiceMock->getCachedExchangeRatesDTOByDate(new DateTime());

        $this->assertInstanceOf(ExchangeRatesDTO::class, $result);
    }

    public function testGetCachedExchangeRatesDTOByDateWhenDataNotExists(): void
    {
        $cacheServiceMock = $this->getMockedCacheService();

        $result = $cacheServiceMock->getCachedExchangeRatesDTOByDate(new DateTime());

        $this->assertNull($result);
    }

    private function getMockedCacheService(): CacheService
    {
        return new CacheService();
    }
}