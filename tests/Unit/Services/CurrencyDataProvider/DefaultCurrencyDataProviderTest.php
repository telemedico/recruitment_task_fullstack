<?php

declare(strict_types=1);

namespace Unit\Services\CurrencyDataProvider;

use App\Dtos\Currency;
use App\Dtos\CurrencyCollection;
use App\Services\CurrencyDataProvider\DefaultCurrencyDataProvider;
use App\Services\ExchangeRates\NbpExchangeRatesProvider;
use App\Services\SpreadCalculator\DefaultSpreadCalculator;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\MockHttpClient;

class DefaultCurrencyDataProviderTest extends TestCase
{
    public function testGetData()
    {
        $expectedResults = (new CurrencyCollection())
            ->add(
                (new Currency())->setName('Dolar amerykański')->setCode('USD')
                    ->setPrice(1)->setSellPrice(1.07)->setBuyPrice(0.95)
            )->add(
                (new Currency())->setName('Euro')->setCode('EUR')->setPrice(2)
                    ->setPrice(1)->setSellPrice(1.07)->setBuyPrice(0.95)
            )->add(
                (new Currency())->setName('Korona czeska')->setCode('CZK')->setPrice(3)
                    ->setPrice(1)->setSellPrice(1.15)->setBuyPrice(null)
            );

        $currencyData = $this->getDataProviderMock()->getData(new DateTime());
        $this->assertInstanceOf(CurrencyCollection::class, $currencyData);
        $this->assertEquals($expectedResults, $currencyData);
    }

    private function getDataProviderMock(): DefaultCurrencyDataProvider
    {
        $exchangeRatesProviderMock = $this->getMockBuilder(NbpExchangeRatesProvider::class)
            ->setConstructorArgs([new MockHttpClient()])
            ->getMock();
        $exchangeRatesProviderMock
            ->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn(
                (new CurrencyCollection())
                    ->add(
                        (new Currency())->setName('Dolar amerykański')->setCode('USD')->setPrice(1)
                    )->add(
                        (new Currency())->setName('Euro')->setCode('EUR')->setPrice(1)
                    )->add(
                        (new Currency())->setName('Korona czeska')->setCode('CZK')->setPrice(1)
                    )
            );
        $parameterBagMock = $this->getMockBuilder(ParameterBagInterface::class)->getMock();
        $parameterBagMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(['USD', 'EUR', 'CZK']);

        return new DefaultCurrencyDataProvider(
            $exchangeRatesProviderMock,
            new DefaultSpreadCalculator(),
            $parameterBagMock
        );
    }
}