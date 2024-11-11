<?php

declare(strict_types=1);

namespace App\RatesApi\ApiProvider;

use App\Config\RatesConfigProvider;
use DateTimeImmutable;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpCurrencyRatesApi implements CurrencyRatesApiInterface
{
    private const API_URL = 'https://api.nbp.pl/api/exchangerates/tables/%s/%s';
    private const CURRENCY_TABLE = 'A';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    public function __construct(
        HttpClientInterface $client,
        RatesConfigProvider $ratesConfigProvider
    ) {
        $this->httpClient = $client;
        $this->ratesConfigProvider = $ratesConfigProvider;
    }

    public function get(array $currencySymbols, DateTimeImmutable $date): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                sprintf(self::API_URL, self::CURRENCY_TABLE, $date->format('Y-m-d')),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]
            );
        } catch (TransportExceptionInterface $e) {
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $rates = json_decode($response->getContent(), true);
        $rates = $rates[0]['rates'] ?? [];
        $rates = $this->restrictCurrencies($rates);

        return $rates;
    }

    private function restrictCurrencies(array $rates): array
    {
        $currencies = $this->ratesConfigProvider->getCurrencyCodes();

        return array_filter($rates, function ($rate) use ($currencies) {
            return in_array($rate['code'], $currencies);
        });
    }
}