<?php

namespace Integration\ExchangeRate;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRateTest extends WebTestCase
{
    public function testConnectivityWithValidDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates?date=2023-05-09');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $rates = json_decode($response->getContent(), TRUE);
        
        $this->assertIsArray($rates);
        $this->assertArrayHasKey('rates', $rates);

        $this->assertCount(5, $rates['rates']);
        $this->assertEquals('USD', $rates['rates'][0]['code']);
        $this->assertEquals(4.0117, $rates['rates'][0]['mid']);
    }

    public function testConnectivityWithWrongDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates?date=2023-01-aa');

        $this->assertResponseStatusCodeSame(400);

        $response = $client->getResponse();
        $this->assertJson($response->getContent());

        $expectedResponse = ['error' => 'Invalid date format. Expected format: YYYY-mm-dd'];
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expectedResponse, $responseData);
    }
}