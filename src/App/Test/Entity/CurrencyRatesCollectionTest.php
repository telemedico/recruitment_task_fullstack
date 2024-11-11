<?php

declare(strict_types=1);

namespace App\Test\Entity;

use App\Entity\CurrencyRate;
use App\Entity\CurrencyRatesCollection;
use PHPUnit\Framework\TestCase;

class CurrencyRatesCollectionTest extends TestCase
{
    /**
     * @var CurrencyRatesCollection
     */
    private $currencyRatesCollection;

    public function setUp(): void
    {
        $this->currencyRatesCollection = new CurrencyRatesCollection(
            [
                new CurrencyRate('EUR', 4.0, 3.90, 4.10),
                new CurrencyRate('BRL', 1.0, 0.85, null),
            ],
            new \DateTimeImmutable('2024-11-07 17:00:00')
        );

        parent::setUp();
    }

    public function testGetDateFormat(): void
    {
        $date = $this->currencyRatesCollection->getDate();

        self::assertEquals('2024-11-07', $date);
    }

    public function testGetExistingCurrencyRate(): void
    {
        $rate = $this->currencyRatesCollection->getRateByCode('EUR');

        self::assertEquals('EUR', $rate->getCode());
    }

    public function testAddNewCurrencyRate(): void
    {
        $newRate = new CurrencyRate('NEW', 1.0, 0.90, 0.10);

        $this->currencyRatesCollection->addCurrencyRate($newRate);

        $newReturned = $this->currencyRatesCollection->getRateByCode('NEW');
        self::assertSame($newRate, $newReturned);
    }
}