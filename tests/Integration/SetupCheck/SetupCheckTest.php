<?php

declare(strict_types=1);

namespace Integration\SetupCheck;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class SetupCheckTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        // test e.g. the profile page
        $client->request(Request::METHOD_GET, '/api/setup-check');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('testParam', $responseData);
    }
}
