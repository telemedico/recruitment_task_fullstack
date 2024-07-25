<?php

declare(strict_types=1);

namespace Unit\Application\Service;

use App\Exchange\Application\Service\CurrencyRateFactory;
use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\ExchangeRateCalculator;
use App\Exchange\Domain\ValueObject\CurrencyCode;
use App\Exchange\Domain\ValueObject\CurrencyName;
use App\Exchange\Domain\ValueObject\ExchangeRate;
use App\Exchange\Infrastructure\Http\ApiCurrencyRate;
use App\Exchange\Infrastructure\Http\ApiCurrencyRateRate;
use PHPUnit\Framework\TestCase;

class CurrencyRateFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $apiCurrencyRateRate = new ApiCurrencyRateRate('144/A/NBP/2024', '2024-07-25', 4.0);
        $apiCurrencyRate = new ApiCurrencyRate('Dollar', 'USD', [$apiCurrencyRateRate]);

        $exchangeRateCalculatorMock = $this->createMock(ExchangeRateCalculator::class);
        $exchangeRateCalculatorMock->method('calculateBuyRate')->willReturn(3.95);
        $exchangeRateCalculatorMock->method('calculateSellRate')->willReturn(4.05);

        $factory = new CurrencyRateFactory($exchangeRateCalculatorMock);
        $currencyRate = $factory->create($apiCurrencyRate);

        $this->assertInstanceOf(CurrencyRate::class, $currencyRate);
        $this->assertEquals(new CurrencyCode('USD'), $currencyRate->getCode());
        $this->assertEquals(new CurrencyName('Dollar'), $currencyRate->getName());
        $this->assertEquals(new ExchangeRate(4.0), $currencyRate->getNbpRate());
        $this->assertEquals(new ExchangeRate(3.95), $currencyRate->getBuyRate());
        $this->assertEquals(new ExchangeRate(4.05), $currencyRate->getSellRate());
    }
}
