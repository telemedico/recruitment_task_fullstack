<?php

namespace Unit\ExchangeRate;

use App\Service\ExchangeRate\ExchangeRatesApiService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Cache\CacheItem;

class ExchangeRatesApiServiceTest extends WebTestCase
{
    public function testGetExchangeRates(): void
    {
        $mockResponse = new MockResponse(json_encode([[
            'table' => 'A',
            'no' => '001/A/NBP/2023',
            'effectiveDate' => '2023-02-09',
            'rates' => [
                [
                    'currency' => 'dolar amerykański',
                    'code' => 'USD',
                    'mid' => 4.0176
                ],
                [
                    'currency' => 'euro',
                    'code' => 'EUR',
                    'mid' => 4.3344
                ]
            ]
        ]]));

        $mockHttpClient = new MockHttpClient($mockResponse);
        
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback(new CacheItem());
            });
        
        $supportedCurrencies = ['USD', 'EUR'];
        $exchangeService = new ExchangeRatesApiService($mockHttpClient, $cache, $supportedCurrencies);

        $rates = $exchangeService->getExchangeRates('2023-02-09');
        $this->assertIsArray($rates);
        $this->assertEquals('2023-02-09', $rates['effectiveDate']);
        $this->assertCount(2, $rates['rates']);
        $this->assertEquals('USD', $rates['rates'][0]['code']);
        $this->assertEquals(4.0176, $rates['rates'][0]['mid']);
    }


}