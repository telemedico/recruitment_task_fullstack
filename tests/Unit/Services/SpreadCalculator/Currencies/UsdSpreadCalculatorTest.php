<?php

namespace Unit\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\Currencies\UsdSpreadCalculator;
use PHPUnit\Framework\TestCase;

class UsdCurrencySpreadCalculatorTest extends TestCase
{
    public function testCalculateBuyPrice()
    {
        $calculator = new UsdSpreadCalculator();
        $currency = (new Currency())
            ->setName('Dolar amerykański')
            ->setCode('USD')
            ->setPrice(1);

        $this->assertEquals(0.95, $calculator->buyPrice($currency));
    }

    public function testReturnNullIfBuyPriceIsNegative()
    {
        $calculator = new UsdSpreadCalculator();
        $currency = (new Currency())
            ->setName('Dolar amerykański')
            ->setCode('USD')
            ->setPrice(0.04);

        $buyPrice = $calculator->buyPrice($currency);
        $this->assertNull($buyPrice);
    }


    public function testCalculateSellPrice()
    {
        $calculator = new UsdSpreadCalculator();
        $currency = (new Currency())
            ->setName('Dolar amerykański')
            ->setCode('USD')
            ->setPrice(1);

        $this->assertEquals(1.07, $calculator->sellPrice($currency));
    }
}
