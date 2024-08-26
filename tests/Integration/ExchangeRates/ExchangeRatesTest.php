<?php

namespace Integration\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Controller\ExchangeRatesController;

class ExchangeRagesTest extends WebTestCase
{
    public function testShowOneWithValidCurrencyAndDate(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/EUR/2024-08-02');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Check if the necessary keys exist in the response data
        $this->assertArrayHasKey('rates', $responseData);
        $this->assertArrayHasKey('currency', $responseData);
        $this->assertArrayHasKey('code', $responseData);
    }

    public function testShowOneWithUnsupportedCurrency(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/XYZ/2024-08-02');
        $this->assertResponseStatusCodeSame(400);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Verify the error message
        $this->assertEquals(ExchangeRatesController::ERR_MSGS['UNSUPPORTED_CURRENCY'], $responseData['error']);
    }

    public function testShowAllWithValidDate(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2024-08-02');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Assuming the API returns an array of rates, we check if it's present
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
    }

    public function testShowOneWithWeekendDate(): void
    {
        $client = static::createClient();

        // Choose a Saturday or Sunday date (e.g., 2024-08-03 is a Saturday)
        $client->request('GET', '/api/exchange-rates/EUR/2024-08-03');

        // Check that the response indicates that rates are not available
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Assuming the API returns a specific message for weekends
        $this->assertEquals(ExchangeRatesController::ERR_MSGS['NO_DATA'], $responseData['error']);
    }

    public function testShowAllWithWeekendDate(): void
    {
        $client = static::createClient();

        // Test a request for a Saturday or Sunday date
        $client->request('GET', '/api/exchange-rates/2024-08-03');

        // Check that the response indicates that rates are not available
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        // Verify the error message or appropriate response
        $this->assertEquals(ExchangeRatesController::ERR_MSGS['NO_DATA'], $responseData['error']);
    }
}
