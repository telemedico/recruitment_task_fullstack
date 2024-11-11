<?php

declare(strict_types=1);

namespace App\Test\Config;

use App\Config\RatesConfigProvider;
use PHPUnit\Framework\TestCase;

class RatesConfigProviderTest extends TestCase
{
    private const CURRENCIES = [
        'EUR' => 'Euro',
        'USD' => 'Dolar Amerykański',
        'CZK' => 'Korona Czeska',
        'IDR' => 'Rupia Indonezyjska',
        'BRL' => 'Real Brazylijski'
    ];

    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    public function setUp(): void
    {
        $this->ratesConfigProvider = new RatesConfigProvider();

        parent::setUp();
    }

    public function testBaseCurrency(): void
    {
        $returned = $this->ratesConfigProvider->getBaseCurrency();

        self::assertEquals('PLN', $returned);
    }

    public function testCurrencyNameReturn(): void
    {
        $name = $this->ratesConfigProvider->getCurrencyName('EUR');
        self::assertEquals('Euro', $name);

        $name = $this->ratesConfigProvider->getCurrencyName('NOT_EXISTING');
        self::assertEquals(null, $name);

        $currenciesAndNames = $this->ratesConfigProvider->getCurrenciesAndNames();
        self::assertEquals(self::CURRENCIES, $currenciesAndNames);
    }

    public function testCurrencies(): void
    {
        $returned = $this->ratesConfigProvider->getCurrencyCodes();

        self::assertIsArray($returned);

        foreach (array_keys(self::CURRENCIES) as $currency) {
            self::assertContains($currency, $returned);
        }
    }

    public function testRelativeRates(): void
    {
        $returned = $this->ratesConfigProvider->getRelativeRates();

        self::assertIsArray($returned);

        foreach ($returned as $entry) {
            self::assertIsArray($entry);

            self::assertArrayHasKey('buy', $entry);
            self::assertArrayHasKey('sell', $entry);

            self::assertIsArray($entry['currencies']);

            foreach ($entry['currencies'] as $currency) {
                self::assertIsString($currency);
            }
        }
    }
}