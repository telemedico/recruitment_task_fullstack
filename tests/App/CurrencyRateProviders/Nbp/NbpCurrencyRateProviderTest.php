<?php

namespace App\CurrencyRateProviders\Nbp;

use App\Dto\CurrencyRateDto;
use App\Dto\CurrencyRatesDto;
use App\Enum\CurrencyEnum;
use App\Service\CurrencyRateCalculator;
use DateTime;
use PHPUnit\Framework\TestCase;

class NbpCurrencyRateProviderTest extends TestCase
{
    private $clientMock;
    private $currencyRateCalculatorMock;

    public function testGetCurrencyRatesSuccess(): void
    {
        $date = new DateTime('2024-10-20');

        $apiResponse = [
            'effectiveDate' => '2024-10-20',
            'rates' => [
                ['currency' => 'US Dollar', 'code' => 'USD', 'mid' => 1.0],
                ['currency' => 'Euro', 'code' => 'EUR', 'mid' => 0.9],
                ['currency' => 'Unknown Currency', 'code' => 'UNKNOWN', 'mid' => 100.0],
            ],
        ];

        $this->clientMock->method('getCurrencyRates')->willReturn($apiResponse);

        $this->currencyRateCalculatorMock->method('__invoke')->willReturnCallback(function ($code, $currency, $mid) {
            return new CurrencyRateDto($code, $currency, $mid, $mid);
        });

        $provider = new NbpCurrencyRateProvider($this->clientMock, $this->currencyRateCalculatorMock);

        $result = $provider->getCurrencyRates($date);

        $this->assertInstanceOf(CurrencyRatesDto::class, $result);
        $this->assertEquals('2024-10-20', $result->getDate()->format('Y-m-d'));
        $this->assertCount(2, $result->getRates());

        $firstRate = $result->getRates()[0];
        $this->assertInstanceOf(CurrencyRateDto::class, $firstRate);
        $this->assertEquals('USD', $firstRate->getCurrency());
        $this->assertEquals('US Dollar', $firstRate->getName());
        $this->assertEquals(1.0, $firstRate->getSell());
        $this->assertEquals(1.0, $firstRate->getBuy());
    }

    public function testGetCurrencyRatesFiltersUnsupportedCurrencies(): void
    {
        $date = new DateTime('2024-10-20');

        $apiResponse = [
            'effectiveDate' => '2024-10-20',
            'rates' => [
                ['currency' => 'US Dollar', 'code' => 'USD', 'mid' => 1.0],
                ['currency' => 'Unknown Currency', 'code' => 'UNKNOWN', 'mid' => 100.0],
                ['currency' => 'Euro', 'code' => 'EUR', 'mid' => 0.9],
            ],
        ];

        $this->clientMock->method('getCurrencyRates')->willReturn($apiResponse);

        $this->currencyRateCalculatorMock->method('__invoke')->willReturnCallback(function ($code, $currency, $mid) {
            return new CurrencyRateDto($code, $currency, $mid, $mid);
        });

        $provider = new NbpCurrencyRateProvider($this->clientMock, $this->currencyRateCalculatorMock);

        $this->assertTrue(CurrencyEnum::supports('USD'));
        $this->assertTrue(CurrencyEnum::supports('EUR'));
        $this->assertFalse(CurrencyEnum::supports('UNKNOWN'));

        $result = $provider->getCurrencyRates($date);

        $this->assertInstanceOf(CurrencyRatesDto::class, $result);
        $this->assertEquals('2024-10-20', $result->getDate()->format('Y-m-d'));

        $this->assertCount(2, $result->getRates());

        $firstRate = $result->getRates()[0];
        $this->assertEquals('USD', $firstRate->getCurrency());
        $secondRate = $result->getRates()[1];
        $this->assertEquals('EUR', $secondRate->getCurrency());
    }

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(NbpCurrencyRateClient::class);
        $this->currencyRateCalculatorMock = $this->createMock(CurrencyRateCalculator::class);
    }
}