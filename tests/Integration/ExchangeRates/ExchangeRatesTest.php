<?php

namespace Integration\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesTest extends WebTestCase
{
    public function testGet(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertIsArray($responseData);
        $responseFirstCurrency = reset($responseData);
        $this->assertIsArray($responseFirstCurrency);
        $this->assertArrayHasKey('currency', $responseFirstCurrency);
        $this->assertArrayHasKey('code', $responseFirstCurrency);
        $this->assertArrayHasKey('mid', $responseFirstCurrency);
        $this->assertArrayHasKey('buyPrice', $responseFirstCurrency);
        $this->assertArrayHasKey('sellPrice', $responseFirstCurrency);
        $this->assertArrayHasKey('todayBuyPrice', $responseFirstCurrency);
        $this->assertArrayHasKey('todaySellPrice', $responseFirstCurrency);
        $this->assertArrayHasKey('todayMid', $responseFirstCurrency);
    }


}
