<?php

declare(strict_types=1);

namespace Unit\Domain\ValueObject;

use App\Exchange\Domain\ValueObject\ExchangeRate;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
    /**
     * @dataProvider validExchangeRateProvider
     */
    public function testExchangeRateCanBeCreated(float $value): void
    {
        $exchangeRate = new ExchangeRate($value);
        $this->assertEquals($value, $exchangeRate->getValue());
    }

    public function validExchangeRateProvider(): \Generator
    {
        yield [4.5];
        yield [0.1];
        yield [9999.99];
    }

    /**
     * @dataProvider invalidExchangeRateProvider
     */
    public function testExchangeRateValidationFails(float $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ExchangeRate($value);
    }

    public function invalidExchangeRateProvider(): \Generator
    {
        yield [-1.0];
        yield [0.0];
    }
}
