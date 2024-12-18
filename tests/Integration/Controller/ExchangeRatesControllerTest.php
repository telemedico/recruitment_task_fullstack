<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testGetExchangeRatesSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('historical', $content);
        $this->assertArrayHasKey('current', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('today', $content);

        foreach (['historical', 'current'] as $section) {
            foreach ($content[$section] as $rate) {
                $this->assertArrayHasKey('code', $rate);
                $this->assertArrayHasKey('currency', $rate);
                $this->assertArrayHasKey('nbpRate', $rate);
                $this->assertArrayHasKey('buyRate', $rate);
                $this->assertArrayHasKey('sellRate', $rate);

                if (in_array($rate['code'], ['EUR', 'USD'])) {
                    $this->assertNotNull($rate['buyRate']);
                } else {
                    $this->assertNull($rate['buyRate']);
                }
            }
        }
    }

    public function testGetExchangeRatesWithInvalidDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates?date=2022-01-01');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $content);
    }

    public function testGetExchangeRatesWithInvalidDateFormat(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates?date=invalid-date');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $content);
    }

    public function testGetExchangeRatesWithBoundaryDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates?date=2023-01-01');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('historical', $content);
        $this->assertArrayHasKey('current', $content);
    }
    public function testGetExchangeRatesNoDateParameter(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('historical', $content);
        $this->assertArrayHasKey('current', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('today', $content);
    }
}
