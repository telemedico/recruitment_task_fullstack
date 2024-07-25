<?php

declare(strict_types=1);

namespace Unit\Domain\ValueObject;

use App\Exchange\Domain\ValueObject\CurrencyName;
use PHPUnit\Framework\TestCase;

class CurrencyNameTest extends TestCase
{
    /**
     * @dataProvider validCurrencyNameProvider
     */
    public function testCurrencyNameCanBeCreated(string $value): void
    {
        $currencyName = new CurrencyName($value);
        $this->assertEquals($value, $currencyName->getValue());
    }

    public function validCurrencyNameProvider(): \Generator
    {
        yield ['Dollar'];
        yield ['Euro'];
        yield ['Yen'];
    }

    /**
     * @dataProvider invalidCurrencyNameProvider
     */
    public function testCurrencyNameValidationFails(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CurrencyName($value);
    }

    public function invalidCurrencyNameProvider(): \Generator
    {
        yield [''];
        yield [str_repeat('a', 256)];
    }
}
