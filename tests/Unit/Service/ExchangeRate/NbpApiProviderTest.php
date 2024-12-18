<?php

namespace App\Tests\Unit\Service\ExchangeRate;

use App\Service\ExchangeRate\NbpApiProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NbpApiProviderTest extends TestCase
{
    private $httpClient;
    private $provider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->provider = new NbpApiProvider($this->httpClient);
    }

    public function testSuccessfulResponse()
    {
        $sampleData = [
            [
                'effectiveDate' => '2023-12-13',
                'rates' => [
                    ['code' => 'EUR', 'currency' => 'euro', 'mid' => 4.5000],
                    ['code' => 'USD', 'currency' => 'dolar amerykański', 'mid' => 4.0000]
                ]
            ]
        ];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode($sampleData));

        $this->httpClient->method('request')->willReturn($response);

        $result = $this->provider->getRatesForDate(new \DateTime());
        $this->assertArrayHasKey('rates', $result);
        $this->assertArrayHasKey('effectiveDate', $result);
    }

    public function testInvalidResponse()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn('invalid json');

        $this->httpClient->method('request')->willReturn($response);

        $this->expectException(\Exception::class);
        $this->provider->getRatesForDate(new \DateTime());
    }

    public function testMissingEffectiveDate()
    {
        $responseData = [
            [
                'rates' => [
                    ['code' => 'EUR', 'currency' => 'euro', 'mid' => 4.5]
                ]
            ]
        ];

        $response = $this->createMock(\Symfony\Contracts\HttpClient\ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode($responseData));

        $this->httpClient->method('request')->willReturn($response);

        $this->expectException(\Exception::class);
        $this->provider->getRatesForDate(new \DateTime());
    }

    public function testEmptyRates()
    {
        $responseData = [
            [
                'effectiveDate' => '2023-12-13',
                'rates' => []
            ]
        ];

        $response = $this->createMock(\Symfony\Contracts\HttpClient\ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode($responseData));

        $this->httpClient->method('request')->willReturn($response);

        $result = $this->provider->getRatesForDate(new \DateTime());
        $this->assertEmpty($result['rates']);
        $this->assertEquals('2023-12-13', $result['effectiveDate']);
    }
    public function testUnsupportedCurrencyFiltering()
    {
        $responseData = [
            [
                'effectiveDate' => '2023-12-13',
                'rates' => [
                    ['code' => 'EUR', 'currency' => 'euro', 'mid' => 4.5],
                    ['code' => 'XXX', 'currency' => 'nieznana', 'mid' => 1.2]
                ]
            ]
        ];

        $response = $this->createMock(\Symfony\Contracts\HttpClient\ResponseInterface::class);
        $response->method('getContent')->willReturn(json_encode($responseData));

        $this->httpClient->method('request')->willReturn($response);

        $result = $this->provider->getRatesForDate(new \DateTime());
        $this->assertCount(1, $result['rates']);
        $this->assertEquals('EUR', $result['rates'][0]['code']);
    }
}
