<?php

namespace Unit\ExchangeRate;

use App\Service\ExchangeRate\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesServiceTest extends WebTestCase {
    public function testProcessExchangeRates(): void {
        $ratesData = [
            "effectiveDate" => "2024-01-01",
            "rates" => [
                ["currency" => "dolar amerykański", "code" => "USD", "mid" => 4.0176],
                ["currency" => "euro", "code" => "EUR", "mid" => 4.3344],
                ["currency" => "real (Brazylia)", "code" => "BRL", "mid" => 0.7047]
            ]
        ];

        $exchangeService = new ExchangeRatesService();
        $rates = $exchangeService->processExchangeRates($ratesData['rates']);

        $this->assertIsArray($rates);
        $this->assertCount(3, $rates);

        $this->assertEquals('EUR', $rates[1]['code']);
        $this->assertEquals(4.3344, $rates[1]['mid']);
        $this->assertEquals(4.2844, $rates[1]['buy']);

        $this->assertEquals('BRL', $rates[2]['code']);
        $this->assertEquals(0.7047, $rates[2]['mid']);
        $this->assertEquals(null, $rates[2]['buy']); 
        $this->assertEquals(0.8547, $rates[2]['sell']);
    }
}
