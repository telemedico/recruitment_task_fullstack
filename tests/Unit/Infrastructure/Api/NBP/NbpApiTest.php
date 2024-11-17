<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Api\NBP;

use App\Infrastruture\Api\NBP\NbpApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class NbpApiTest extends TestCase
{
    /**
     * @var NbpApi
     */
    private $api;

    /**
     * @var HttpClientInterface&MockObject
     */
    private $client;

    /**
     * @var LoggerInterface&MockObject
     */
    private $logger;

    /**
     * @var ResponseInterface&MockObject
     */
    private $response;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);

        $this->api = new NbpApi($this->client, $this->logger, 'http://nbp.api.base.url.test');
    }

    public function testFetchExchangeRatesForDateReturnsRatesWhenApiCallSucceeds(): void
    {
        $date = new \DateTimeImmutable('2024-11-15');
        $expectedResponseData = [
            [
                'table' => 'A',
                'no' => '221/A/NBP/2024',
                'effectiveDate' => '2024-11-15',
                'rates' => [
                    ['currency' => 'USD', 'code' => 'USD', 'mid' => 4.2],
                    ['currency' => 'EUR', 'code' => 'EUR', 'mid' => 4.7],
                ],
            ],
        ];

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);

        $this->response
            ->expects(self::once())
            ->method('toArray')
            ->with(false)
            ->willReturn($expectedResponseData);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                'http://nbp.api.base.url.test/api/exchangerates/tables/A/2024-11-15/?format=json'
            )
            ->willReturn($this->response);

        $this->logger
            ->expects(self::never())
            ->method('warning');

        $result = $this->api->fetchExchangeRatesForDate($date);

        self::assertSame($expectedResponseData, $result);
    }

    public function testFetchExchangeRatesForDateReturnsEmptyArrayWhenDateIsFuture(): void
    {
        $futureDate = new \DateTimeImmutable('2099-01-01');

        $this->client
            ->expects(self::never())
            ->method('request');

        $this->logger
            ->expects(self::once())
            ->method('warning')
            ->with('Request to NBP API cannot be done.', ['date' => $futureDate]);

        $result = $this->api->fetchExchangeRatesForDate($futureDate);

        self::assertSame([], $result);
    }

    public function testFetchExchangeRatesForDateHandlesNonOkResponse(): void
    {
        $date = new \DateTimeImmutable('2024-11-15');

        $this->response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_BAD_REQUEST);

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                'http://nbp.api.base.url.test/api/exchangerates/tables/A/2024-11-15/?format=json'
            )
            ->willReturn($this->response);

        $this->logger
            ->expects(self::once())
            ->method('warning')
            ->with('Unexpected response from NBP API', ['response' => $this->response]);

        $result = $this->api->fetchExchangeRatesForDate($date);

        $this->assertSame([], $result);
    }
}
