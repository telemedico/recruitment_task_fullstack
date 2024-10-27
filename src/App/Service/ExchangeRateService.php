<?php

namespace App\Service;

use App\Service\ExchangeRates\ExchangeRateStrategyFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService {
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getExchangeRatesByDate(string $date): array {
        $currencies = Currency::SUPPORTED_CURRENCIES;
        $result = [];

        foreach ($currencies as $currency) {
            $currencyName = Currency::getCurrencyName($currency);
            $averageRate = $this->fetchAverageRate($currency, $date);
            $strategy = ExchangeRateStrategyFactory::getStrategy($currency);
            $result[] = [
                'currencyName' => $currencyName,
                'currency' => $currency,
                'averageRate' => $averageRate,
                'buyRate' => $strategy->calculateBuyRate($averageRate),
                'sellRate' => $strategy->calculateSellRate($averageRate),
            ];
        }

        return $result;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function fetchAverageRate(string $currency, string $date): float {
        $response = $this->httpClient->request('GET', "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}/{$date}/?format=json");
        $data = $response->toArray();
        return $data['rates'][0]['mid'];
    }
}
