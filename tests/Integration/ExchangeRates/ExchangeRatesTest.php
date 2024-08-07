<?php

namespace Integration\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ExchangeRatesTest extends WebTestCase
{
    public function testGetWhenDateIsNotSetAndIsErrorResponse(): void
    {
        $client = $this->makeRequest();
        $this->expectError();
    }

    public function testGetWhenDateIsSetAndIsErrorResponse(): void
    {
        $client = $this->makeRequest('2024-01-01');
        $this->expectError();
    }

    public function testGetWhenDateIsSetAndValueIsWrong(): void
    {
        $client = $this->makeRequest('2020-01-01');
        $this->expectError();
    }

    public function testGetWhenDateIsNotSetAndIsSuccessResponse(): void
    {
        $client = $this->makeRequest();
        $this->assertResponseIsSuccessful();
    }

    public function testGetWhenDateIsSetAndIsSuccessResponse(): void
    {
        $client = $this->makeRequest('2023-01-01');
        $this->assertResponseIsSuccessful();
    }

    private function makeRequest(?string $date = null): ?Crawler
    {
        $client = static::createClient();

        return $client->request(
            'GET',
            '/api/exchange-rates',
            ($date)
                ? ['date' => $date]
                : []
        );
    }
}