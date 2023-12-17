<?php

namespace Unit\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\Currencies\EurSpreadCalculator;
use PHPUnit\Framework\TestCase;

class EurCurrencySpreadCalculatorTest extends TestCase
{
    public function testCalculateBuyPrice()
    {
        $calculator = new EurSpreadCalculator();
        $currency = (new Currency())
            ->setName('Euro')
            ->setCode('EUR')
            ->setPrice(1);

        $this->assertEquals(0.95, $calculator->buyPrice($currency));
    }

    public function testCalculateSellPrice()
    {
        $calculator = new EurSpreadCalculator();
        $currency = (new Currency())
            ->setName('Euro')
            ->setCode('EUR')
            ->setPrice(1);

        $this->assertEquals(1.07, $calculator->sellPrice($currency));
    }
}
