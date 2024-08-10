<?php

namespace Tests\Unit;

use App\Service\CurrencySpread;
use PHPUnit\Framework\TestCase;

class CurrencySpreadTest extends TestCase
{
    /**
     * @var CurrencySpread
     */
    private $currencySpread;

    protected function setUp(): void
    {
        $this->currencySpread = new CurrencySpread(
            [
                'EUR' => ['buySpread' => 0.05, 'sellSpread' => 0.07],
                'USD' => ['buySpread' => 0.05, 'sellSpread' => 0.07],
                'CZK' => ['sellSpread' => 0.15],
                'AUD' => ['buySpread' => 0.08]
            ]
        );
    }

    public function testSupportedCurrencies()
    {
        $this->assertEquals(['EUR', 'USD', 'CZK', 'AUD'], $this->currencySpread->supportedCurrencies());
    }

    public function testCalculateBuyPrice()
    {
        $this->assertEquals('4.15', $this->currencySpread->calculateBuyPrice('USD', '4.20'));
    }

    public function testCalculateSellPrice()
    {
        $this->assertEquals('4.27', $this->currencySpread->calculateSellPrice('USD', '4.20'));
    }

    public function testCalculateNoBuyPrice()
    {
        $this->assertNull($this->currencySpread->calculateBuyPrice('CZK', '2.20'));
    }

    public function testCalculateNoSellPrice()
    {
        $this->assertNull($this->currencySpread->calculateSellPrice('AUD', '1.25'));
    }
}
