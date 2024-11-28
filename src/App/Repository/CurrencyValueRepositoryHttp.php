<?php

namespace App\Repository;

use App\Exception\CurrencyValueNotFoundException;
use App\Model\CurrencyValue;
use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyValueRepositoryHttp implements CurrencyValueRepositoryInterface
{
    /** @var HttpClientInterface $client */
    private $client;
    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function findByCurrencyCodeAndDate(string $code, ?DateTime $date = null): CurrencyValue
    {
        $requestedDate = $date ?? new DateTime();
        $formattedDate = $requestedDate->format('Y-m-d');
        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.nbp.pl/api/exchangerates/rates/A/%s/%s?format=json',
                $code,
                $formattedDate
            )
        );
        if ($response->getStatusCode() === 404) {
            throw new CurrencyValueNotFoundException(
                sprintf(
                    "Currency value data for currency of code: '%s' and date: '%s' not found",
                    $code,
                    $formattedDate
                )
            );
        }
        return $this->mapResponseToCurrencyValue($response->toArray());
    }

    private function mapResponseToCurrencyValue(array $responseBody): CurrencyValue
    {
        $rate = reset($responseBody['rates']);
        return new CurrencyValue(
            $responseBody['code'],
            $responseBody['currency'],
            new DateTime($rate['effectiveDate']),
            $rate['mid']
        );
    }
}