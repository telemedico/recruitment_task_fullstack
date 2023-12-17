<?php

namespace Integration\SetupCheck;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SetupCheckTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/currency-rates/2023-11-29');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        print_r($responseData);
    }


}