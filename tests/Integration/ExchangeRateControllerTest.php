<?php
namespace Integration\ExchangeRatesController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-nbp-table');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('effectiveDate', $responseData);
        $this->assertArrayHasKey('onlyLatestData', $responseData);
        $this->assertArrayHasKey('currencies', $responseData);
    }


}