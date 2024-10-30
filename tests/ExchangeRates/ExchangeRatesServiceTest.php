<?php

namespace ExchangeRates;

use App\ExchangeRates\ExchangeRatesService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesServiceTest extends TestCase
{

    public function testGetOfficeRatesWithValidDate()
    {
        $date = '2022-01-01';

        $mockResponse = new MockResponse(json_encode(
            [[
                'rates' => [
                    [
                        'currency' => 'EUR',
                        'code' => 'EUR',
                        'mid' => 1.0
                    ]
                ]
            ]]
        ));

        $mockHttpClient = new MockHttpClient($mockResponse);
        $serviceInstance = new ExchangeRatesService($mockHttpClient);

        $officeRates = $serviceInstance->getOfficeRates($date);

        $this->assertTrue(is_array($officeRates));
        $this->assertNotEmpty($officeRates);
    }

    public function testGetOfficeRatesWithInvalidDate()
    {
        $date = 'Invalid Date';
        $client = $this->createMock(HttpClientInterface::class);
        $serviceInstance = new ExchangeRatesService($client);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format. Please use YYYY-MM-DD.');

        $serviceInstance->getOfficeRates($date);
    }
}