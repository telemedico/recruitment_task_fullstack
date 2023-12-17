<?php

declare(strict_types=1);

namespace App\Services\ExchangeRates;

use App\Dtos\Currency;
use App\Dtos\CurrencyCollection;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    const API_URL = 'http://api.nbp.pl/api/exchangerates/tables/A/{date}/?format=json';
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getExchangeRates(DateTime $date): CurrencyCollection
    {
        $data = $this->callNbpApi($date);
        $results = new CurrencyCollection();

        foreach ($data[0]['rates'] as $item) {
            $currency = (new Currency())
                ->setName($item['currency'])
                ->setCode($item['code'])
                ->setPrice($item['mid']);

            $results->add($currency);
        }

        return $results;
    }

    private function callNbpApi(DateTime $date): array
    {
        $response = $this->httpClient->request(Request::METHOD_GET, $this->prepareUrl($date));

        switch ($response->getStatusCode()) {
            case 404:
                throw new NoDataException();
            case 200:
                return json_decode($response->getContent(), true);
            default:
                throw new ExchangeRatesProviderException(
                    "Niespodziewany kod odpowiedzi NBP api: {$response->getStatusCode()}."
                );
        }

    }

    private function prepareUrl(DateTime $date): string
    {
        return str_replace("{date}", $date->format('Y-m-d'), self::API_URL);
    }
}