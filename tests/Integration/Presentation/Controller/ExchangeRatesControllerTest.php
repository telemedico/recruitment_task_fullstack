<?php

declare(strict_types=1);

namespace App\Tests\Integration\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

final class ExchangeRatesControllerTest extends WebTestCase
{
    public function testInvokeReturnsExchangeRates(): void
    {
        $apiResponseForUserDate = new MockResponse(\json_encode([[
            'rates' => [
                ['currency' => 'Euro', 'code' => 'EUR', 'mid' => 4.5],
                ['currency' => 'US dollar', 'code' => 'USD', 'mid' => 3.0],
            ],
        ]]), ['http_code' => 200]);

        $apiResponseForLatestDate = new MockResponse(\json_encode([[
            'rates' => [
                ['currency' => 'Euro', 'code' => 'EUR', 'mid' => 4.6],
                ['currency' => 'US dollar', 'code' => 'USD', 'mid' => 4.0],
            ],
        ]]), ['http_code' => 200]);

        $mockedHttpClient = new MockHttpClient([$apiResponseForUserDate, $apiResponseForLatestDate]);

        $client = self::createClient();
        $client->getContainer()->set(MockHttpClient::class, $mockedHttpClient);

        $client->request(
            'GET',
            '/api/exchange-rates',
            [
                'userDate' => '2024-11-01',
                'latestDate' => '2024-11-15',
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $expectedResponse = <<<JSON
        {
          "rates": [
            {
              "currencyCode": "EUR",
              "currencyName": "Euro",
              "latestBidRate": 4.55,
              "latestAskRate": 4.67,
              "latestNbpRate": 4.6,
              "userDateBidRate": 4.45,
              "userDateAskRate": 4.57,
              "userDateNbpRate": 4.5
            },
            {
              "currencyCode": "USD",
              "currencyName": "US dollar",
              "latestBidRate": 3.95,
              "latestAskRate": 4.07,
              "latestNbpRate": 4,
              "userDateBidRate": 2.95,
              "userDateAskRate": 3.07,
              "userDateNbpRate": 3
            }
          ],
          "userDate": "2024-11-01",
          "latestDate": "2024-11-15"
        }
JSON;

        $responseContent = $client->getResponse()->getContent();

        self::assertJsonStringEqualsJsonString($expectedResponse, $responseContent);
    }
}
