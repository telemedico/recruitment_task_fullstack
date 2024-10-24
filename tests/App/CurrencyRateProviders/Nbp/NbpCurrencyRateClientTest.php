<?php

namespace App\CurrencyRateProviders\Nbp;

use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NbpCurrencyRateClientTest extends TestCase
{
    private $httpClientMock;
    private $responseMock;

    public function testGetCurrencyRatesSuccess(): void
    {
        $date = new DateTime('2024-10-20');

        $jsonResponse = json_encode([
            [
                'rates' => [
                    ['currency' => 'USD', 'code' => 'USD', 'mid' => 1.0],
                ],
            ],
        ]);

        $this->responseMock->method('getContent')->willReturn($jsonResponse);
        $this->httpClientMock->method('request')->willReturn($this->responseMock);

        $client = new NbpCurrencyRateClient($this->httpClientMock);
        $result = $client->getCurrencyRates($date);

        $this->assertArrayHasKey('rates', $result);
        $this->assertCount(1, $result['rates']);
        $this->assertEquals('USD', $result['rates'][0]['code']);
        $this->assertEquals(1.0, $result['rates'][0]['mid']);
    }

    public function testGetCurrencyRatesNullDate(): void
    {
        $jsonResponse = json_encode([
            [
                'rates' => [
                    ['currency' => 'USD', 'code' => 'USD', 'mid' => 1.0],
                ],
            ],
        ]);

        $this->responseMock->method('getContent')->willReturn($jsonResponse);
        $this->httpClientMock->method('request')->willReturn($this->responseMock);

        $client = new NbpCurrencyRateClient($this->httpClientMock);
        $result = $client->getCurrencyRates(null);

        $this->assertArrayHasKey('rates', $result);
        $this->assertCount(1, $result['rates']);
        $this->assertEquals('USD', $result['rates'][0]['code']);
        $this->assertEquals(1.0, $result['rates'][0]['mid']);
    }

    public function testGetCurrencyRatesThrowsNbpServiceExceptionOnInvalidResponse(): void
    {
        $jsonResponse = json_encode([[]]);

        $this->responseMock->method('getContent')->willReturn($jsonResponse);
        $this->httpClientMock->method('request')->willReturn($this->responseMock);

        $client = new NbpCurrencyRateClient($this->httpClientMock);

        $this->expectException(NbpServiceException::class);
        $this->expectExceptionMessage('Invalid response. No data found.');

        $client->getCurrencyRates(new DateTime('2024-10-20'));
    }

    public function testGetCurrencyRatesThrowsNbpServiceExceptionOnHttpClientException(): void
    {
        $this->responseMock->method('getContent')->willThrowException(
            $this->createMock(HttpExceptionInterface::class)
        );

        $this->httpClientMock->method('request')->willReturn($this->responseMock);

        $client = new NbpCurrencyRateClient($this->httpClientMock);

        $this->expectException(NbpServiceException::class);
        $this->expectExceptionMessage('Invalid response. No data found.');

        $client->getCurrencyRates(new DateTime('2024-10-20'));
    }

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }
}