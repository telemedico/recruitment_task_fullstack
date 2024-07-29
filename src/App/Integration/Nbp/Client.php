<?php

declare(strict_types=1);

namespace App\Integration\Nbp;

use Exception;

class Client
{
    const METHOD_GET = 'GET';

    const API_URL = 'https://api.nbp.pl/api/exchangerates/';
    const API_RESPONSE_FORMAT = 'json';

    private $endpoints = [
        'rates' => 'rates/A/'
    ];

    /**
     * @throws Exception
     */
    public function getExchangeRates(string $currencyCode, ?string $date)
    {
        if (empty($date)) {
            return $this->call($this->prepareUrl(self::METHOD_GET, $this->endpoints['rates'], [$currencyCode]));
        }
        return $this->call($this->prepareUrl(self::METHOD_GET, $this->endpoints['rates'], [$currencyCode, $date]));
    }

    private function call(string $url)
    {
        return json_decode(file_get_contents($url));
    }

    /**
     * @throws Exception
     */
    private function prepareUrl(string $method, string $endpoint, array $params = []): string
    {
        $url = self::API_URL . $endpoint . implode('/', $params) . '?format=' . self::API_RESPONSE_FORMAT;
        if($method === self::METHOD_GET) {
            return self::API_URL . $endpoint . implode('/', $params) . '?format=' . self::API_RESPONSE_FORMAT;
        }
        throw new Exception('Unknown method');
    }
}
