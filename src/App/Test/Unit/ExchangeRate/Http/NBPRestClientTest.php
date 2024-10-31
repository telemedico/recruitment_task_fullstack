<?php

declare(strict_types = 1);

namespace App\Test\Unit\ExchangeRate\Http;

use App\Constant\Formats;
use App\Exception\IncorrectDateException;
use App\ExchangeRate\DTO\ExchangeRate;
use App\ExchangeRate\Http\NBPRestClient;
use App\ExchangeRate\UsedCurrenciesProvider;
use App\Test\Unit\Data\NBPApiResponseData;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NBPRestClientTest extends TestCase
{
    /**
     * @var CacheInterface|MockObject
     */
    private $cacheMock;

    /**
     * @var NBPRestClient
     */
    private $NBPRestClient;

    public function setUp(): void
    {
        $usedCurrenciesProviderMock = $this->createMock(UsedCurrenciesProvider::class);
        $usedCurrenciesProviderMock->method('getCurrencies')
            ->willReturn(['USD']);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->cacheMock = $this->createMock(CacheInterface::class);

        $this->NBPRestClient = new NBPRestClient(
            'http://some-website.com',
            $usedCurrenciesProviderMock,
            $httpClientMock,
            $this->cacheMock
        );
    }

    public function testGetRates_correctData_returnRates(): void
    {
        $this->cacheMock->method('get')->willReturn(NBPApiResponseData::getCorrectData()[0]['rates']);

        $this->NBPRestClient->setDate(new DateTime());

        $this->cacheMock->expects($this->once())
            ->method('get')
            ->with('NBPRestClient-USD-' . (new DateTime())->format(Formats::DEFAULT_DATE_FORMAT));

        $result = $this->NBPRestClient->getRates();

        $rate = new ExchangeRate();
        $rate->setRate(3.9355);
        $rate->setCurrency('USD');

        $this->assertEquals([
            'USD' => $rate
        ], $result);
    }

    public function testGetRates_incorrectDate_throwException(): void
    {
        $this->NBPRestClient->setDate((new DateTime())->modify('-2 day'));
        $this->NBPRestClient->setMinDate((new DateTime())->modify('-1 day'));

        $this->expectException(IncorrectDateException::class);

        $this->NBPRestClient->getRates();
    }
}