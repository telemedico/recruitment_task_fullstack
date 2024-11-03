<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testGetExchangeRatesWithoutDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('currencies', $responseData);
        $this->assertArrayHasKey('dateOfRates', $responseData);
    }

    public function testGetExchangeRatesWithValidDate(): void
    {
        $client = static::createClient();
        $date = '2024-01-02'; 
        $client->request('GET', '/api/exchange-rates', ['date' => $date]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('currencies', $responseData);
        $this->assertArrayHasKey('dateOfRates', $responseData);
    }

    public function testGetExchangeRatesWithInvalidDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates', ['date' => 'invalid-date']);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testGetExchangeRatesWithFutureDate(): void
    {
        $client = static::createClient();
        $futureDate = (new \DateTime('+1 year'))->format('Y-m-d');
        $client->request('GET', '/api/exchange-rates', ['date' => $futureDate]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
    }

    public function testGetExchangeRatesBefore2023(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates', ['date' => '2022-12-31']);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testApiFailureHandling(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/api/exchange-rates', ['date' => 'invalid-date']);
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testGetExchangeRatesOnWeekend(): void
    {
        $client = static::createClient();
        $date = '2024-08-10'; 
        $client->request('GET', '/api/exchange-rates', ['date' => $date]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('messageGPWNotWorking', $responseData);
        $this->assertEquals('Giełda papierów wartościowych w wybranej dacie nie pracuje', $responseData['messageGPWNotWorking']);
    }

    public function testGetExchangeRatesOnDaysFree(): void
    {
        $client = static::createClient();
        $date = '2024-01-01'; 
        $client->request('GET', '/api/exchange-rates', ['date' => $date]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('messageGPWNotWorking', $responseData);
        $this->assertEquals('Giełda papierów wartościowych w wybranej dacie nie pracuje', $responseData['messageGPWNotWorking']);
    }

    public function testCurrencyCalculations(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates');
        
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        
        foreach ($responseData['currencies'] as $currency) {
            $this->assertArrayHasKey('currentRates', $currency);
            $this->assertArrayHasKey('NBPValue', $currency['currentRates']);
            
        }
    }
}
