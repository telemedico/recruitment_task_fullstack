<?php

namespace App\Service;

use App\Exception\CurrencyException;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CurrencyService
{
    private const CURRENCY_FACTOR = [
        'USDEUR'  => [
            'buy'  => -0.05,
            'sell' => 0.07,
        ],
        'DEFAULT' => [
            'buy'  => null,
            'sell' => 0.15,
        ],
    ];

    private const SUPPORTED_CURRENCIES = [
        'USD' => self::CURRENCY_FACTOR['USDEUR'],
        'EUR' => self::CURRENCY_FACTOR['USDEUR'],
        'CZK' => self::CURRENCY_FACTOR['DEFAULT'],
        'IDR' => self::CURRENCY_FACTOR['DEFAULT'],
        'BRL' => self::CURRENCY_FACTOR['DEFAULT'],
    ];

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param DateTime|null $date
     * @return array[]
     * @throws CurrencyException
     */
    public function getCurrenciesForDate(?DateTime $date = null): array
    {
        if (null === $date) {
            $date = new DateTime();
        }

        try {
            $todayCurrencies = $this->client->request(
                'GET',
                'http://api.nbp.pl/api/exchangerates/tables/A/today/',
                ['headers' => ['Accept' => 'application/json']]
            );

            $dateCurrencies = $this->client->request(
                'GET',
                "http://api.nbp.pl/api/exchangerates/tables/A/{$date->format('Y-m-d')}",
                ['headers' => ['Accept' => 'application/json']]
            );

            return [
                'today' => $this->prepareCurrencies(json_decode($todayCurrencies->getBody()->getContents(), true)[0]),
                'date'  => $this->prepareCurrencies(json_decode($dateCurrencies->getBody()->getContents(), true)[0]),
            ];
        } catch (Throwable $e) {
            throw new CurrencyException('Nie udało się pobrać danych dla wybranego dnia', Response::HTTP_BAD_REQUEST);
        }
    }

    private function prepareCurrencies(array $data): array
    {
        foreach ($data['rates'] as $rate) {
            if (!array_key_exists($rate['code'], self::SUPPORTED_CURRENCIES)) {
                continue;
            }

            $response[$rate['code']] = $this->modifyRate($rate);
        }

        return $response ?? [];
    }

    /**
     * @param array $rate
     * @return array
     * @throws CurrencyException
     */
    private function modifyRate(array $rate): array
    {
        if (!array_key_exists($rate['code'], self::SUPPORTED_CURRENCIES)) {
            throw new CurrencyException("The {$rate['code']} currency is not supported");
        }

        $factor = self::SUPPORTED_CURRENCIES[$rate['code']];

        $rate['buy']  = null !== $factor['buy'] ? $rate['mid'] + $factor['buy'] : null;
        $rate['sell'] = null !== $factor['sell'] ? $rate['mid'] + $factor['sell'] : null;

        return $rate;
    }
}
