<?php

declare(strict_types=1);

namespace Unit\Domain\ValueObject;

use App\Exchange\Domain\ValueObject\CurrencyCode;
use PHPUnit\Framework\TestCase;

class CurrencyCodeTest extends TestCase
{
    /**
     * @dataProvider validCurrencyCodeProvider
     */
    public function testCurrencyCodeCanBeCreated(string $value): void
    {
        $currencyCode = new CurrencyCode($value);
        $this->assertEquals($value, $currencyCode->getValue());
    }

    public function validCurrencyCodeProvider(): \Generator
    {
        yield ['USD'];
        yield ['EUR'];
        yield ['JPY'];
    }

    /**
     * @dataProvider invalidCurrencyCodeProvider
     */
    public function testCurrencyCodeValidationFails(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CurrencyCode($value);
    }

    public function invalidCurrencyCodeProvider(): \Generator
    {
        yield ['US'];
        yield ['EURO'];
        yield [''];
    }
}
