<?php

namespace App\Service;

use GuzzleHttp\Client;

class NbpApiService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://api.nbp.pl/api/exchangerates/tables/A/']);
    }

    public function httpGet(string $url): array
    {
        return $this->httpRequest($url, 'GET');
    }

    private function httpRequest(string $url, string $method): array
    {
        $data      = $this->client->request($method, $url, ['headers' => ['Accept' => 'application/json']]);
        $arrayData = json_decode($data->getBody()->getContents(), true);

        return array_shift($arrayData);
    }
}
