<?php

namespace Unit\Services\SpreadCalculator\Currencies;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\Currencies\IdrSpreadCalculator;
use PHPUnit\Framework\TestCase;

class IdrSpreadCalculatorTest extends TestCase
{
    public function testCalculateBuyPrice()
    {
        $calculator = new IdrSpreadCalculator();
        $currency = (new Currency())
            ->setName('Rupia Indonezyjska')
            ->setCode('IDR')
            ->setPrice(1);

        $this->assertEquals(null, $calculator->buyPrice($currency));
    }

    public function testCalculateSellPrice()
    {

        $calculator = new IdrSpreadCalculator();
        $currency = (new Currency())
            ->setName('Rupia Indonezyjska')
            ->setCode('IDR')
            ->setPrice(0.00123456);

        $this->assertEquals(0.15123456, $calculator->sellPrice($currency));
    }
}
