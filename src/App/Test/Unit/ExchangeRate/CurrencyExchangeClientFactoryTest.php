<?php

declare(strict_types = 1);

namespace App\Test\Unit\ExchangeRate;

use App\Constant\Formats;
use App\ExchangeRate\CurrencyExchangeClientFactory;
use App\ExchangeRate\CurrencyExchangeClientInterface;
use App\ExchangeRate\ExchangeRatesRequestDataModifierInterface;
use DateTime;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeClientFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $exchangeRateRequestMock = $this->createMock(CurrencyExchangeClientInterface::class);
        $rateModifiersMock = [
            $this->createMock(ExchangeRatesRequestDataModifierInterface::class)
        ];

        $dateMock = new DateTime();
        $minDateMock = (new DateTime())->modify('-30 days')->format(Formats::DEFAULT_DATE_FORMAT);

        $currencyExchangeClientFactory = new CurrencyExchangeClientFactory(
            $exchangeRateRequestMock,
            $dateMock,
            $minDateMock,
            $rateModifiersMock
        );

        $exchangeRateRequestMock->expects($this->once())
            ->method('setDate')
            ->with($dateMock);

        $exchangeRateRequestMock->expects($this->once())
            ->method('setMinDate')
            ->with(new DateTime($minDateMock));

        $exchangeRateRequestMock->expects($this->exactly(count($rateModifiersMock)))
            ->method('addDataModifier')
            ->with($rateModifiersMock[0]);

        $result = $currencyExchangeClientFactory->create();

        $this->assertInstanceOf(CurrencyExchangeClientInterface::class, $result);
    }
}