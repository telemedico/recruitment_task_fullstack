<?php

namespace Integration\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesTest extends WebTestCase
{
    public function testFetchAllForDate(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2024-08-09');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());

        $this->assertStringContainsString('"code":"USD"', $response->getContent());
        $this->assertStringContainsString('"code":"EUR"', $response->getContent());
        $this->assertStringContainsString('"buyPrice":', $response->getContent());
        $this->assertStringContainsString('"sellPrice":', $response->getContent());
    }
}
