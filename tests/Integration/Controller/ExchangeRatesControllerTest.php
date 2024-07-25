<?php
namespace Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\TraceableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::ensureKernelShutdown();

        self::bootKernel();
    }

    public function testGetExchangeRatesSuccess(): void
    {
        $response = new MockResponse(json_encode([
            'table' => 'A',
            'currency' => 'euro',
            'code' => 'EUR',
            'rates' => [
                ['no' => '144/A/NBP/2024', 'effectiveDate' => '2024-07-25', 'mid' => 4.2971]
            ]
        ]), ['http_code' => 200]);

        $client = self::createClient();
        $client->getContainer()->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new TraceableHttpClient(new MockHttpClient($response)));
        $client->request('GET', '/api/exchange-rates/2024-07-25');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetExchangeRatesInvalidDate(): void
    {
        $response = new MockResponse(json_encode([
            'table' => 'A',
            'currency' => 'euro',
            'code' => 'EUR',
            'rates' => [
                ['no' => '144/A/NBP/2024', 'effectiveDate' => '2024-07-25', 'mid' => 4.2971]
            ]
        ]), ['http_code' => 200]);

        $client = self::createClient();
        $client->getContainer()->set(HttpClientInterface::class, new TraceableHttpClient(new MockHttpClient($response)));
        $client->request('GET', '/api/exchange-rates/invalid-date');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Invalid date format.', $client->getResponse()->getContent());
    }

    public function testGetExchangeRatesNotFound(): void
    {
        $response = new MockResponse('', ['http_code' => 404]);

        $client = self::createClient();
        $client->getContainer()->set(HttpClientInterface::class, new TraceableHttpClient(new MockHttpClient($response)));
        $client->request('GET', '/api/exchange-rates/1900-01-01');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('No exchange rates found for the given date.', $client->getResponse()->getContent());
    }

    public function testGetExchangeRatesServerError(): void
    {
        $response = new MockResponse('', ['http_code' => 500]);

        $client = self::createClient();
        $client->getContainer()->set(HttpClientInterface::class, new TraceableHttpClient(new MockHttpClient($response)));
        $client->request('GET', '/api/exchange-rates/2024-07-25');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('HTTP request failed with status code 500', $client->getResponse()->getContent());
    }
}
