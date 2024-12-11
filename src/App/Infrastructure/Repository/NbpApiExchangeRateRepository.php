<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Currency;
use App\Domain\ExchangeRateRepositoryInterface;
use App\Infrastructure\Factory\ExchangeRateFactory;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NbpApiExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    private const GET_EXCHANGE_RATES_LIST_URI = 'api/exchangerates/tables/A';

    private $nbpApiUrl;
    private $httpClient;
    private $logger;
    private $exchangeRateFactory;

    public function __construct(
        string $nbpApiUrl,
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        ExchangeRateFactory $exchangeRateFactory
    ) {
        $this->nbpApiUrl = $nbpApiUrl;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->exchangeRateFactory = $exchangeRateFactory;
    }

    public function getList(DateTimeImmutable $date): array
    {
        $url = sprintf(
            '%s/%s/%s?%s',
            rtrim($this->nbpApiUrl, '/'),
            ltrim(self::GET_EXCHANGE_RATES_LIST_URI, '/'),
            $date->format('Y-m-d'),
            http_build_query([
                'format' => 'json',
            ])
        );

        try {
            $response = $this->httpClient->request('GET', $url, []);
            $exchangeRates = $response->toArray()[0]['rates'] ?? [];

            $result = [];

            foreach ($exchangeRates as $exchangeRate) {
                if (in_array($exchangeRate['code'], Currency::getAvailableCurrencies(), true)) {
                    $result[] = $this->exchangeRateFactory->buildFromArray($exchangeRate);
                }
            }

            return $result;
        } catch (
            TransportExceptionInterface
            |ClientExceptionInterface
            |DecodingExceptionInterface
            |RedirectionExceptionInterface
            |ServerExceptionInterface $e
        ) {
            $this->logger->critical(
                '[NbpApiExchangeRateRepository] Error while getting exchange rates list',
                [
                    'exception' => $e->getMessage(),
                    'url' => $url,
                    'stackTrace' => $e->getTraceAsString(),
                ]
            );

            return [];
        }
    }
}
