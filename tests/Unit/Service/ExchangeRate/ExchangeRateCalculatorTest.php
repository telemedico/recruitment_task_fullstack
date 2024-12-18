<?php

namespace App\Tests\Unit\Service\ExchangeRate;

use App\Service\ExchangeRate\ExchangeRateCalculator;
use PHPUnit\Framework\TestCase;

class ExchangeRateCalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ExchangeRateCalculator();
    }

    public function testMajorCurrencyCalculations()
    {
        $rates = [
            ['code' => 'EUR', 'mid' => 4.5000, 'currency' => 'euro'],
            ['code' => 'USD', 'mid' => 4.0000, 'currency' => 'dolar amerykański']
        ];

        foreach ($rates as $rate) {
            $result = $this->calculator->calculateRate($rate);
            $this->assertEquals($rate['mid'] - 0.05, $result['buyRate']);
            $this->assertEquals($rate['mid'] + 0.07, $result['sellRate']);
        }
    }

    public function testOtherCurrencyCalculations()
    {
        $rates = [
            ['code' => 'CZK', 'mid' => 0.1800, 'currency' => 'korona czeska'],
            ['code' => 'IDR', 'mid' => 0.0003, 'currency' => 'rupia indonezyjska'],
            ['code' => 'BRL', 'mid' => 0.8000, 'currency' => 'real brazylijski']
        ];

        foreach ($rates as $rate) {
            $result = $this->calculator->calculateRate($rate);
            $this->assertNull($result['buyRate']);
            $this->assertEqualsWithDelta($rate['mid'] + 0.15, $result['sellRate'], 0.0001, 'Sell rate is not as expected');
        }
    }

    public function testInvalidData()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calculator->calculateRate(['invalid' => 'data']);
    }

    public function testVerySmallRate()
    {
        $rate = ['code' => 'EUR', 'mid' => 0.0001, 'currency' => 'euro'];
        $result = $this->calculator->calculateRate($rate);

        $this->assertEqualsWithDelta(0.0001 - 0.05, $result['buyRate'], 0.0001);
        $this->assertEqualsWithDelta(0.0001 + 0.07, $result['sellRate'], 0.0001);
    }

    public function testVeryLargeRate()
    {
        $rate = ['code' => 'USD', 'mid' => 1000, 'currency' => 'dolar amerykański'];
        $result = $this->calculator->calculateRate($rate);

        $this->assertEqualsWithDelta(999.95, $result['buyRate'], 0.0001);
        $this->assertEqualsWithDelta(1000.07, $result['sellRate'], 0.0001);
    }

    public function testRoundingTo4DecimalPlaces()
    {
        $rate = ['code' => 'EUR', 'mid' => 4.123456, 'currency' => 'euro'];
        $result = $this->calculator->calculateRate($rate);

        $this->assertEquals(4.0735, $result['buyRate']);
        $this->assertEquals(4.1935, $result['sellRate']);
    }
}
