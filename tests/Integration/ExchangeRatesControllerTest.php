<?php

namespace Integration\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testGetRatesSuccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates', [
            'date' => '2024-07-19',
            'currencies' => ['USD', 'EUR']
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Validate the response structure and content
        $this->assertIsArray($responseData);
        $this->assertCount(2, $responseData); // Expecting two currency entries

        // // Check USD entry
        $this->assertArrayHasKey(0, $responseData);
        $this->assertArrayHasKey('currency', $responseData[0]);
        $this->assertArrayHasKey('code', $responseData[0]);
        $this->assertArrayHasKey('todayMid', $responseData[0]);
        $this->assertArrayHasKey('dateMid', $responseData[0]);

        $this->assertEquals('dolar amerykański', $responseData[0]['currency']);
        $this->assertEquals('USD', $responseData[0]['code']);
        $this->assertEquals(3.9461, $responseData[0]['todayMid']);
        $this->assertEquals(3.9461, $responseData[0]['dateMid']);

        // // Check EUR entry
        $this->assertArrayHasKey(1, $responseData);
        $this->assertArrayHasKey('currency', $responseData[1]);
        $this->assertArrayHasKey('code', $responseData[1]);
        $this->assertArrayHasKey('todayMid', $responseData[1]);
        $this->assertArrayHasKey('dateMid', $responseData[1]);

        $this->assertEquals('euro', $responseData[1]['currency']);
        $this->assertEquals('EUR', $responseData[1]['code']);
        $this->assertEquals(4.293, $responseData[1]['todayMid']);
        $this->assertEquals(4.293, $responseData[1]['dateMid']);
    }

    public function testGetRatesBadRequest(): void
    {
        $client = static::createClient();

        // Testing invalid date format
        $client->request('GET', '/api/exchange-rates', [
            'date' => 'invalid-date',
            'currencies' => ['USD', 'EUR']
        ]);

        $this->assertResponseStatusCodeSame(400);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid date format or date is older than 2023.', $responseData['message']);

        // Testing out of boundaries date
        $client->request('GET', '/api/exchange-rates', [
            'date' => '2022-01-01',
            'currencies' => ['USD', 'EUR']
        ]);
        $this->assertResponseStatusCodeSame(400);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid date format or date is older than 2023.', $responseData['message']);

        // Testing invalid currencies format
        $client->request('GET', '/api/exchange-rates', [
            'date' => '2024-07-19',
            'currencies' => 'USD'
        ]);
        $this->assertResponseStatusCodeSame(400);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Currencies parameter must be an array.', $responseData['message']);
    }

    public function testGetRatesNotFound(): void
    {
        $client = static::createClient();

        // Simulate a date that does not exist in the service (assuming the service handles it properly)
        $client->request('GET', '/api/exchange-rates', [
            'date' => '2023-01-01', // Old date for testing purposes
            'currencies' => ['USD', 'EUR']
        ]);
        $this->assertResponseStatusCodeSame(404);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Exchange rates not found for the given date.', $responseData['message']);
    }
}
