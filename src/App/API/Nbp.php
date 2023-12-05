<?php

declare(strict_types=1);

namespace App\API;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * http://api.nbp.pl/
 * https://www.currency-iso.org/en/home/tables/table-a1.html tabela z walutami
 */
final class Nbp
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function call(string $url): array
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            $response->getContent();
            return json_decode($response->getContent());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function getTableForDate(string $date): array
    {
        return $this->call('http://api.nbp.pl/api/exchangerates/tables/a/' . $date . '/');
    }
}

