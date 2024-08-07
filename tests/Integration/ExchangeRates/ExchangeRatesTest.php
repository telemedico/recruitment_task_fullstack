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

        foreach (['today', 'date'] as $key) {
            $this->assertArrayHasKey($key, $responseData);

            foreach (self::SUPPORTED_CURRENCIES as $currency) {
                $this->assertArrayHasKey($currency, $responseData[$key]);
                $this->assertArrayHasKey('currency', $responseData[$key][$currency]);
                $this->assertArrayHasKey('code', $responseData[$key][$currency]);
                $this->assertArrayHasKey('mid', $responseData[$key][$currency]);
                $this->assertArrayHasKey('buy', $responseData[$key][$currency]);
                $this->assertArrayHasKey('sell', $responseData[$key][$currency]);
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
