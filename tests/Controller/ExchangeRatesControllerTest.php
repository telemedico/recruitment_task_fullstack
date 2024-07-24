<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testGetExchangeRatesWithValidDate()
    {
        $client = static::createClient();

        $date = '2024-07-22';
        $client->request('GET', "/api/exchange-rates?date=$date");

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseContent = $client->getResponse()->getContent();
        $data = json_decode($responseContent, true);

        $this->assertIsArray($data);

        $currenciesToCheck = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

        foreach ($currenciesToCheck as $currency) {
            $found = false;
            foreach ($data as $item) {
                if ($item['currency'] === $currency) {
                    $found = true;
                    $this->assertArrayHasKey('averageRate', $item);
                    $this->assertArrayHasKey('buyRate', $item);
                    $this->assertArrayHasKey('sellRate', $item);
                    break;
                }
            }
            $this->assertTrue($found, "Currency '$currency' not found in the response.");
        }
    }

    public function testGetExchangeRatesWithInvalidDate()
    {
        $client = static::createClient();

        $date = '2020-01-01';
        $client->request('GET', "/api/exchange-rates?date=$date");

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }
}