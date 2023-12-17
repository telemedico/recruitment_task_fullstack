<?php

namespace Unit\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\Currencies\DefaultCurrencySpreadCalculator;
use PHPUnit\Framework\TestCase;

class DefaultCurrencySpreadCalculatorTest extends TestCase
{
    public function testCalculateBuyPrice()
    {
        $calculator = new DefaultCurrencySpreadCalculator();
        $currency = (new Currency())
            ->setName('Korona czeska')
            ->setCode('CZK')
            ->setPrice(1);

        $this->assertEquals(null, $calculator->buyPrice($currency));
    }

    public function testCalculateSellPrice()
    {

        $calculator = new DefaultCurrencySpreadCalculator();
        $currency = (new Currency())
            ->setName('Korona czeska')
            ->setCode('CZK')
            ->setPrice(1);

        $this->assertEquals(1.15, $calculator->sellPrice($currency));
    }
}