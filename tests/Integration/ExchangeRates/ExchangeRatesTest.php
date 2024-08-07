<?php

namespace Integration\ExchangeRates;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesTest extends WebTestCase
{
    private const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'CZK', 'IDR', 'BRL'];

    public function testGetExchangeRates(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/get-currencies');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('today', $responseData);
        $this->assertArrayHasKey('date', $responseData);

        foreach (['today', 'date'] as $item) {
            foreach (self::SUPPORTED_CURRENCIES as $currency) {
                $this->assertArrayHasKey($currency, $responseData[$item]);
                $this->assertArrayHasKey('currency', $responseData[$item][$currency]);
                $this->assertArrayHasKey('code', $responseData[$item][$currency]);
                $this->assertArrayHasKey('mid', $responseData[$item][$currency]);
                $this->assertArrayHasKey('buy', $responseData[$item][$currency]);
                $this->assertArrayHasKey('sell', $responseData[$item][$currency]);
            }
        }

    }

    public function testGetExchangeRatesNoData(): void
    {
        $client = static::createClient();

        $date = new DateTime('tomorrow');

        $client->request('GET', '/api/get-currencies', ['date' => $date->format('Y-m-d')]);
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }
}
