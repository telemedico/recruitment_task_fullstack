<?php

declare(strict_types=1);

namespace Integration\UI\Api\Action;

use App\Domain\Currency;
use App\Domain\ExchangeRate;
use App\Domain\ExchangeRateRepositoryInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetExchangeRatesListActionTest extends WebTestCase
{
    public function test_get_exchange_rates_list_today_without_errors(): void
    {
        $client = self::createClient();

        $this->mockExternalApi($client);

        $client->request('GET', '/api/v1/exchange-rates');

        self::assertResponseIsSuccessful();

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertCount(3, $content);
        self::assertEquals(3.95, $content[0]['exchangeRate']['buyRate']);
        self::assertEquals(4.07, $content[0]['exchangeRate']['sellRate']);

        self::assertEquals(3.45, $content[1]['exchangeRate']['buyRate']);
        self::assertEquals(3.57, $content[1]['exchangeRate']['sellRate']);

        self::assertNull($content[2]['exchangeRate']['buyRate']);
        self::assertEquals(1.15, $content[2]['exchangeRate']['sellRate']);
    }

    public function test_get_exchange_rates_list_with_another_date_without_errors(): void
    {
        $client = self::createClient();

        $this->mockExternalApi($client);

        $client->request('GET', '/api/v1/exchange-rates', [
            'requestDate' => '2024-01-01',
        ]);

        self::assertResponseIsSuccessful();

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertCount(3, $content);
        self::assertEquals(1.95, $content[0]['exchangeRate']['buyRate']);
        self::assertEquals(2.07, $content[0]['exchangeRate']['sellRate']);

        self::assertEquals(4.45, $content[1]['exchangeRate']['buyRate']);
        self::assertEquals(4.57, $content[1]['exchangeRate']['sellRate']);

        self::assertNull($content[2]['exchangeRate']['buyRate']);
        self::assertEquals(5.15, $content[2]['exchangeRate']['sellRate']);
    }

    public function test_get_error_when_try_get_exchange_rates_list_with_invalid_date(): void
    {
        $client = self::createClient();

        $this->mockExternalApi($client);

        $client->request('GET', '/api/v1/exchange-rates', [
            'requestDate' => (new DateTimeImmutable())->modify('+5 days')->format('Y-m-d'),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    private function mockExternalApi(KernelBrowser $client): void
    {
        $mock = $this->createMock(ExchangeRateRepositoryInterface::class);

        $client->getContainer()->set(ExchangeRateRepositoryInterface::class, $mock);

        $mock->method('getList')->willReturnCallback(function (DateTimeImmutable $date) {
            if ($date->format('Y-m-d') === '2024-01-01') {
                return [
                    new ExchangeRate(new Currency('USD', 'Dolar Amerykański'), 2.0),
                    new ExchangeRate(new Currency('EUR', 'Euro'), 4.5),
                    new ExchangeRate(new Currency('CZK', 'Korona Czeska'), 5),
                ];
            }

            return [
                new ExchangeRate(new Currency('USD', 'Dolar Amerykański'), 4.0),
                new ExchangeRate(new Currency('EUR', 'Euro'), 3.5),
                new ExchangeRate(new Currency('CZK', 'Korona Czeska'), 1),
            ];
        });
    }
}
