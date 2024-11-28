<?php

namespace Unit\Service;

use App\Exception\InvalidPriceCommissionRateException;
use App\Service\CurrencyPriceCalculator;
use PHPUnit\Framework\TestCase;

class CurrencyPriceCalculatorTest extends TestCase
{
    /** @var CurrencyPriceCalculator */
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new CurrencyPriceCalculator();
    }

    public function testCalculateBuyPriceWithCorrectInputIsSuccessful(): void
    {
        $baseValue = 4.50;
        $rate = 0.05;
        $result = $this->calculator->calculateBuyPrice($baseValue, $rate);

        $this->assertSame(4.45, $result);
    }
    public function testCalculateSellPriceWithCorrectInputIsSuccessful(): void
    {
        $baseValue = 4.50;
        $rate = 0.05;
        $result = $this->calculator->calculateSellPrice($baseValue, $rate);

        $this->assertSame(4.55, $result);
    }

    // We could write tests for more unsuccessful cases
    public function testCalculateSellPriceWithIncorrectInputThrowsException(): void
    {
        $this->expectException(InvalidPriceCommissionRateException::class);

        $baseValue = 4.50;
        $rate = -0.05;
        $this->calculator->calculateBuyPrice($baseValue, $rate);
    }
}