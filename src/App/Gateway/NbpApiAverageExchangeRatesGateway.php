<?php

declare(strict_types=1);

namespace App\Gateway;

use App\Exception\CannotFetchAverageExchangeRates;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class NbpApiAverageExchangeRatesGateway implements AverageExchangeRatesGateway
{
    private const NBP_API_EXCHANGE_RATES_JSON = 'https://api.nbp.pl/api/exchangerates/tables/A/%s/?format=json';
    /**
     * @var Client
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return AverageExchangeRateDto[]
     */
    public function fetchRates(DateTimeImmutable $dateTime, string ...$currenciesCodes): array
    {
        $rates = [];
        try {
            $response = $this->client->request('GET', $this->createNbpApiUrl($dateTime));
            foreach ($this->fetchAllRatesDataFromResponse($response) as $rate) {
                if (in_array($rate['code'], $currenciesCodes)) {
                    $rates[] = new AverageExchangeRateDto($rate['currency'], $rate['code'], (string)$rate['mid']);
                }
            }
        } catch (ExceptionInterface $e) {
            throw new CannotFetchAverageExchangeRates(
                'Nie mogę pobrać informacji o średnim kursie', $e->getCode(), $e
            );
        }

        return $rates;
    }

    private function createNbpApiUrl(DateTimeImmutable $dateTime): string
    {
        return sprintf(self::NBP_API_EXCHANGE_RATES_JSON, $dateTime->format('Y-m-d'));
    }

    /**
     * @return array
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function fetchAllRatesDataFromResponse(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return [];
        }
        return json_decode($response->getContent(false), true)[0]['rates'] ?? [];
    }
}
