<?php

namespace Unit\Services\ExchangeRates;

use App\Dtos\CurrencyCollection;
use App\Services\ExchangeRates\ExchangeRatesProviderException;
use App\Services\ExchangeRates\NbpExchangeRatesProvider;
use App\Services\ExchangeRates\NoDataException;
use DateTime;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class NbpExchangeRatesProviderTest extends TestCase
{
    public function testGetExchangeRates()
    {
        $response = new MockResponse(file_get_contents(__DIR__.'/data/nbp_success_response.json'));
        $httpClient = new MockHttpClient($response);
        $exchangeRateProvider = new NbpExchangeRatesProvider($httpClient);

        $result = $exchangeRateProvider->getExchangeRates(new DateTime('2023-11-30'));

        $this->assertInstanceOf(CurrencyCollection::class, $result);
        $this->assertEquals(33, $result->count());
    }

    public function testExceptionIsThrownWhenThereIsNoData()
    {
        $response = new MockResponse('', ['http_code' => 404]);
        $httpClient = new MockHttpClient($response);
        $exchangeRateProvider = new NbpExchangeRatesProvider($httpClient);

        $this->expectException(NoDataException::class);
        $exchangeRateProvider->getExchangeRates(new DateTime('2023-01-01'));
    }

    public function testExceptionIsThrownWhenThereIsProblemWithApi()
    {
        $response = new MockResponse('', ['http_code' => 500]);
        $httpClient = new MockHttpClient($response);
        $exchangeRateProvider = new NbpExchangeRatesProvider($httpClient);

        $this->expectException(ExchangeRatesProviderException::class);
        $exchangeRateProvider->getExchangeRates(new DateTime('2023-11-30'));
    }

    public function testPrepareUrl()
    {
        $exchangeRatesProvider = new NbpExchangeRatesProvider(new MockHttpClient());
        $reflection = new ReflectionClass($exchangeRatesProvider);
        $method = $reflection->getMethod('prepareUrl');
        $method->setAccessible(true);

        $result = $method->invoke($exchangeRatesProvider, new DateTime('2023-11-30'));

        $this->assertEquals('http://api.nbp.pl/api/exchangerates/tables/A/2023-11-30/?format=json', $result);
    }
}
