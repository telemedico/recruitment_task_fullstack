<?php

declare(strict_types=1);

namespace Unit\Application\Service;

use App\Exchange\Application\Exception\NoExchangeRatesFoundException;
use App\Exchange\Application\Service\CurrencyRateFactory;
use App\Exchange\Application\Service\CurrencyService;
use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Exchange\Domain\ValueObject\CurrencyCode;
use App\Exchange\Domain\ValueObject\CurrencyName;
use App\Exchange\Domain\ValueObject\ExchangeRate;
use App\Exchange\Infrastructure\Http\ApiCurrencyRate;
use App\Shared\Modules\RestClient\Exceptions\RestClientResponseException;
use PHPUnit\Framework\TestCase;

class CurrencyServiceTest extends TestCase
{
    public function testGetExchangeRates(): void
    {
        $apiCurrencyRate = new ApiCurrencyRate('USD', 'Dollar', [
            ['mid' => 4.0],
        ]);

        $currencyRateApiClientMock = $this->createMock(CurrencyRateApiClientInterface::class);
        $currencyRateApiClientMock->method('getExchangeRate')->willReturn($apiCurrencyRate);

        $currencyRateFactoryMock = $this->createMock(CurrencyRateFactory::class);
        $currencyRateFactoryMock->method('create')->willReturn(new CurrencyRate(
            new CurrencyCode('USD'),
            new CurrencyName('Dollar'),
            new ExchangeRate(4.0),
            new ExchangeRate(3.95),
            new ExchangeRate(4.05)
        ));

        $currencyService = new CurrencyService($currencyRateApiClientMock, $currencyRateFactoryMock, [['code' => 'USD']]);

        $exchangeRates = $currencyService->getExchangeRates(new \DateTimeImmutable('2024-07-25'));

        $this->assertCount(1, $exchangeRates);
        $this->assertInstanceOf(CurrencyRate::class, $exchangeRates[0]);
    }

    public function testGetExchangeRatesWithError(): void
    {
        $currencyRateApiClientMock = $this->createMock(CurrencyRateApiClientInterface::class);
        $currencyRateApiClientMock->method('getExchangeRate')->willThrowException(new RestClientResponseException('API error', 404));

        $currencyRateFactoryMock = $this->createMock(CurrencyRateFactory::class);

        $currencyService = new CurrencyService($currencyRateApiClientMock, $currencyRateFactoryMock, [['code' => 'USD']]);

        $this->expectException(NoExchangeRatesFoundException::class);

        $currencyService->getExchangeRates(new \DateTimeImmutable('2024-07-25'));
    }
}
