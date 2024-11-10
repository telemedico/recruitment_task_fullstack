<?php

declare(strict_types=1);

namespace App\RatesApi\Nbp;

use App\Config\RatesConfigProvider;
use App\RatesApi\CurrencyRatesApiInterface;
use DateTimeImmutable;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpCurrencyRatesApi implements CurrencyRatesApiInterface
{
    private const API_URL = 'https://api.nbp.pl/api/exchangerates/tables/%s/%s';
    private const CURRENCY_TABLE = 'A'; //todo: is that the only table needed for the future of this project?

    private $httpClient;
    private $cache;

    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        RatesConfigProvider $ratesConfigProvider
    ) {
        $this->httpClient = $client;
        $this->cache = $cache;
        $this->ratesConfigProvider = $ratesConfigProvider;
    }

    public function get(array $currencySymbols, DateTimeImmutable $date, bool $tryEarlierDate = false): array
    {
        $rates = $this->fetchRates($date, $tryEarlierDate);

        $rates = $this->restrictCurrencies($rates);

        /*$this->cache->get('nbp_rates', function ($item) {
            //TODO: zmienić czas cache na 24h albo na czas do 12:00 kiedy to NBP publikuje nowe kursy
            $item->expiresAfter(1);
            return $this->fetchRates();
        });*/
        return $rates;
    }

    private function fetchRates(DateTimeImmutable $date, bool $tryEarlierDate = false): array
    {
        $tries = 0;
        do {
            if ($tries > 0) {
                $date = new DateTimeImmutable($date->format('Y-m-d') . ' -1 DAY');
            }
            $response = $this->fetchRate($date);
        } while (empty($response) && $tryEarlierDate && ++$tries < 5);

        return $response[0]['rates'] ?? [];
    }

    private function fetchRate(DateTimeImmutable $date): array
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
            //todo: log this
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            //todo: log this
            return [];
        }

        return json_decode($response->getContent(), true);
    }

    private function restrictCurrencies(array $rates): array
    {
        $currencies = $this->ratesConfigProvider->getCurrencies();

        return array_filter($rates, function ($rate) use ($currencies) {
            return in_array($rate['code'], $currencies);
        });
    }
}