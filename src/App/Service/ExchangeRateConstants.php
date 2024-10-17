<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private const API_URL = 'https://api.nbp.pl/api/exchangerates/rates';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getExchangeRate(string $table, string $currency, string $date): ?array
    {
        $url = sprintf('%s/%s/%s/%s/?format=json', self::API_URL, $table, $currency, $date);

        try {
            $response = $this->client->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }
        } catch (\Exception $e) {
            error_log("Exception when fetching rates: " . $e->getMessage());
        }

        return null;
    }
}
