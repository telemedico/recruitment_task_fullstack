<?php

namespace Integration\SetupCheck;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesTest extends WebTestCase
{
    public function testGetData(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2023-12-12');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
    }

    public function testNoDataForGivenDate(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2023-12-16');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

}
