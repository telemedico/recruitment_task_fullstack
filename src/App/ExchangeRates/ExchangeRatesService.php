<?php

declare(strict_types=1);

namespace App\ExchangeRates;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesService
{
    private $client;

    // Obsługiwane waluty
    private const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

    // Różnice dla kursów EUR i USD
    private const EUR_USD_BUY_MARGIN = 0.05;
    private const EUR_USD_SELL_MARGIN = 0.07;

    // Różnica dla kursów sprzedaży pozostałych walut
    private const OTHER_SELL_MARGIN = 0.15;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchExchangeRates(?string $date = null): array
    {
        $url = 'https://api.nbp.pl/api/exchangerates/tables/A/';
        if ($date) {
            $url .= $date . '/';
        }
        $url .= '?format=json';

        $response = $this->client->request('GET', $url);
        $data = $response->toArray();

        return $data[0]['rates'] ?? [];
    }

    public function getOfficeRates(?string $date = null): array
    {
        if ($date === null) {
            $date = (new \DateTime())->format('Y-m-d');
        }

        if (!$this->isValidDate($date)) {
            throw new \InvalidArgumentException('Invalid date format. Please use YYYY-MM-DD.');
        }

        //if $date is weekend, set to friday
        $date = date('Y-m-d', strtotime($date));
        $dayOfWeek = date('w', strtotime($date));
        if ($dayOfWeek == 0) {
            $date = date('Y-m-d', strtotime($date . ' -2 days'));
        } elseif ($dayOfWeek == 6) {
            $date = date('Y-m-d', strtotime($date . ' -1 days'));
        }

        $rates = $this->fetchExchangeRates($date);
        $officeRates = [];

        foreach ($rates as $rate) {
            if (!in_array($rate['code'], self::SUPPORTED_CURRENCIES)) {
                continue;
            }

            $buyRate = null;
            $sellRate = null;

            if ($rate['code'] === 'EUR' || $rate['code'] === 'USD') {
                $buyRate = $rate['mid'] - self::EUR_USD_BUY_MARGIN;
                $sellRate = $rate['mid'] + self::EUR_USD_SELL_MARGIN;
            } else {
                $sellRate = $rate['mid'] + self::OTHER_SELL_MARGIN;
            }

            $officeRates[] = [
                'currency' => $rate['currency'],
                'code' => $rate['code'],
                'buyRate' => $buyRate,
                'sellRate' => $sellRate,
                'mid' => $rate['mid'],
            ];
        }

        return $officeRates;
    }

    private function isValidDate(string $date): bool
    {
        // Sprawdzenie formatu daty
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
