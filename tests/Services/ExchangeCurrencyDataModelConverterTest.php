<?php

namespace Services\ExchangeCurrencyDataModelConverterTest;

use App\Utils\PHPUnitUtils;
use PHPUnit\Framework\TestCase;
use App\Service\ExchangeCurrencyDataModelConverter;

class ExchangeCurrencyDataModelConverterForTest extends ExchangeCurrencyDataModelConverter {

    public function calculateCurrencyPrices(bool $onlyLatestData, Array $item, Float|null $currentMid): Array {
        return parent::calculateCurrencyPrices($onlyLatestData, $item, $currentMid);
    }

    public function getAmountMultiplied(string $currencyCode): int  {
        return parent::getAmountMultiplied($currencyCode);
    }

    public function getSellCommision(string $currencyCode): Float|null  {
        return parent::getSellCommision($currencyCode);
    }

    public function getBuyCommision(string $currencyCode): Float|null  {
        return parent::getBuyCommision($currencyCode);
    }

    
    public function calculateDates(Array $response): Array {
        return parent::calculateDates($response);
    }

}

class ExchangeCurrencyDataModelConverterForTest2 extends ExchangeCurrencyDataModelConverter {

    private $mock;

    public function setMock($mock) {
        $this->mock = $mock;
    }

    protected function calculateCurrencyPrices(bool $onlyLatestData, Array $item, Float|null $currentMid): Array {
        return $this->mock->calculateCurrencyPrices($onlyLatestData, $item, $currentMid);
    }
    
    public function calculateExchangeDataModelLatestOnly(array $response): Array {
        return parent::calculateExchangeDataModelLatestOnly($response);
    }

    public function calculateExchangeDataModelWithHistorical(array $response): Array {
        return parent::calculateExchangeDataModelWithHistorical($response);
    }

}

class ExchangeCurrencyDataModelConverterTest extends TestCase
{

    public function testCalculateCurrencyPricesEUR_USD(): void
    {
        $converter = new ExchangeCurrencyDataModelConverterForTest();

        $this->assertEquals(
            $converter->calculateCurrencyPrices(true, ['code' => 'USD', 'mid' => 1], null), 
            [
                'code' => 'USD',
                'mid' => 1,
                'nbp' => 1,
                'buy' => 1.05,
                'sell' => 1.05,
                'key' => 'USD',
                'amountMultiplied' => 1
            ]
        );

        $this->assertEquals(
            $converter->calculateCurrencyPrices(true, ['code' => 'EUR', 'mid' => 1], null), 
            [
                'code' => 'EUR',
                'mid' => 1,
                'nbp' => 1,
                'buy' => 1.05,
                'sell' => 1.05,
                'key' => 'EUR',
                'amountMultiplied' => 1
            ]
        );
    }

    public function testCalculateCurrencyPricesOtherCurrency(): void
    {
        $converter = new ExchangeCurrencyDataModelConverterForTest();

        $this->assertEquals(
            $converter->calculateCurrencyPrices(false, ['code' => 'IDR', 'mid' => .00001], 2), 
            [
                'code' => 'IDR',
                'mid' => .00001,
                'nbp' => 0.01,
                'buy' => null,
                'sell' => 0.16,
                'key' => 'IDR',
                'amountMultiplied' => 1000,
                'currentMid' => 2.0,
                'currentNbp' => 2000.0,
                'currentBuy' => null,
                'currentSell' => 2000.15
            ]
        );
      
        $this->assertEquals(
            $converter->calculateCurrencyPrices(false, ['code' => 'CHK', 'mid' => 1], 2), 
            [
                'code' => 'CHK',
                'mid' => 1,
                'nbp' => 1,
                'buy' => null,
                'sell' => 1.15,
                'key' => 'CHK',
                'amountMultiplied' => 1,
                'currentMid' => 2.0,
                'currentNbp' => 2.0,
                'currentBuy' => null,
                'currentSell' => 2.15
            ]
        );

    }

    public function testGetAmountMultiplied(): void
    {
        $converter = new ExchangeCurrencyDataModelConverterForTest();
        
        $this->assertEquals($converter->getAmountMultiplied('USD'), 1);
        $this->assertEquals($converter->getAmountMultiplied('BRL'), 1);
        $this->assertEquals($converter->getAmountMultiplied('IDR'), 1000);
        $this->assertEquals($converter->getAmountMultiplied('NOM'), 1);
        $this->assertEquals($converter->getAmountMultiplied(''), 1);
    }


    public function testGetBuyCommision(): void
    {
        $converter = new ExchangeCurrencyDataModelConverterForTest();

        $this->assertEquals($converter->getBuyCommision('USD'), 0.05);
        $this->assertEquals($converter->getBuyCommision('EUR'), 0.05);
        $this->assertEquals($converter->getBuyCommision('IDR'), null);
        $this->assertEquals($converter->getBuyCommision('NOM'), null);
        $this->assertEquals($converter->getBuyCommision(''), null);
    }

    public function testGetSellCommision(): void
    {
        $converter = new ExchangeCurrencyDataModelConverterForTest();

        $this->assertEquals($converter->getSellCommision('USD'), 0.05);
        $this->assertEquals($converter->getSellCommision('EUR'), 0.05);
        $this->assertEquals($converter->getSellCommision('IDR'), 0.15);
        $this->assertEquals($converter->getSellCommision('NOM'), 0.15);
        $this->assertEquals($converter->getSellCommision(''), 0.15);

    }

    public function testCalculateExchangeDataModelLatestOnly() {
        $converter = new ExchangeCurrencyDataModelConverterForTest2();
        $mock = $this->createMock(ExchangeCurrencyDataModelConverterForTest::class);
        $converter->setMock($mock);

        $mock->expects($this->exactly(2))
             ->method('calculateCurrencyPrices')
             ->willReturn([]);

        $converter->calculateExchangeDataModelLatestOnly(
            [
                latest => [
                    rates => [
                        [code=>'USD'], 
                        [code=>'EUR'],
                        [code=>'AUD']
                    ]
                ]
            ]
        );
    }

    public function testCalculateExchangeDataModelWithHistorical() {
        $converter = new ExchangeCurrencyDataModelConverterForTest2();
        $mock = $this->createMock(ExchangeCurrencyDataModelConverterForTest::class);
        $converter->setMock($mock);

        $mock->expects($this->exactly(2))
             ->method('calculateCurrencyPrices')
             ->willReturn([]);

        $converter->calculateExchangeDataModelWithHistorical(
            [
                latest => [
                    rates => [
                        [code=>'USD', 'mid'=>1], 
                        [code=>'SSA', 'mid'=>1],
                        [code=>'ZUE', 'mid'=>1],
                        [code=>'BRL', 'mid'=>1], 
                    ]
                ],
                historical => [
                    rates => [
                        [code=>'USD', 'mid'=>1], 
                        [code=>'SSA', 'mid'=>1],
                        [code=>'ZUE', 'mid'=>1],
                        [code=>'BRL', 'mid'=>1], 
                    ]
                ]
            ]
        );
    }
}