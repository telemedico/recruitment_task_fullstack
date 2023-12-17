<?php

namespace Unit\Services\SpreadCalculator;

use App\Dtos\Currency;
use App\Services\SpreadCalculator\DefaultSpreadCalculator;
use PHPUnit\Framework\TestCase;

class DefaultSpreadCalculatorTest extends TestCase
{
    private $calculator;
    private $usd;
    private $czk;
    private $eur;
    private $brl;

    public function setUp(): void
    {
        $this->calculator = new DefaultSpreadCalculator();

        $this->usd = (new Currency())
            ->setName('Dolar amerykański')
            ->setCode('USD')
            ->setPrice(1);
        $this->czk = (new Currency())
            ->setName('Korona czeska')
            ->setCode('CZK')
            ->setPrice(1);
        $this->eur = (new Currency())
            ->setName('Euro')
            ->setCode('EUR')
            ->setPrice(1);
        $this->brl = (new Currency())
            ->setName('Real (Brazylia)')
            ->setCode('BRL')
            ->setPrice(1);
    }

    public function testCalculateBuyPrice()
    {
        $this->assertEquals(0.95, $this->calculator->buyPrice($this->usd));
        $this->assertEquals(0.95, $this->calculator->buyPrice($this->eur));
        $this->assertEquals(null, $this->calculator->buyPrice($this->czk));
        $this->assertEquals(null, $this->calculator->buyPrice($this->brl));

    }

    public function testCalculateSellPrice()
    {
        $this->assertEquals(1.07, $this->calculator->sellPrice($this->usd));
        $this->assertEquals(1.07, $this->calculator->sellPrice($this->eur));
        $this->assertEquals(1.15, $this->calculator->sellPrice($this->czk));
        $this->assertEquals(1.15, $this->calculator->sellPrice($this->brl));
    }
}
