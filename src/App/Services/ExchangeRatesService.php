<?php

namespace App\Services;

use App\Http\Nbp\NbpClient;
use DateTime;

class ExchangeRatesService
{

    public const BUY_CURRENCIES = [
        'EUR' => 0.05,
        'USD' => 0.05,
    ];
    public const SELL_CURRENCIES = [
        'EUR' => 0.07,
        'USD' => 0.07,
        'CZK' => 0.15,
        'IDR' => 0.15,
        'BRL' => 0.15,
    ];

    public const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

    /**
     * @var NbpClient
     */
    private $nbpClient;

    public function __construct(NbpClient $nbpClient)
    {
        $this->nbpClient = $nbpClient;
    }

    public function getRates(DateTime $date): array
    {
        return array_merge($this->getTablesC($date), $this->getTablesA($date));
    }

    public function getTablesC(DateTime $date)
    {
        $currenciesRates = [];
        $rates = $this->nbpClient->getTablesForDate($date, 'C');

        if (!array_key_exists(0, $rates)) {
            return [];
        }

        if (!array_key_exists('rates', $rates[0])) {
            return [];
        }

        foreach ($rates[0]['rates'] as $rate) {
            if (!array_key_exists($rate['code'], self::BUY_CURRENCIES)) {
                continue;
            }

            $currenciesRates[] = [
                'code' => $rate['code'],
                'currency' => $rate['currency'],
                'rate_buy' => sprintf("%.2f", $rate['bid'] + self::BUY_CURRENCIES[$rate['code']]),
                'rate_sell' => sprintf("%.2f", $rate['ask'] + self::SELL_CURRENCIES[$rate['code']]),
            ];
        }

        return $currenciesRates;
    }

    public function getTablesA(DateTime $date)
    {
        $currenciesRates = [];
        $rates = $this->nbpClient->getTablesForDate($date);

        if (!array_key_exists(0, $rates)) {
            return [];
        }

        if (!array_key_exists('rates', $rates[0])) {
            return [];
        }

        $currencyCodes = array_diff(self::SUPPORTED_CURRENCIES, array_keys(self::BUY_CURRENCIES));

        foreach ($rates[0]['rates'] as $rate) {
            if (!in_array($rate['code'], $currencyCodes)) {
                continue;
            }

            $rateBuy = null;
            if (array_key_exists($rate['code'], self::BUY_CURRENCIES)) {
                $rateBuy = sprintf("%.2f", $rate['mid'] + self::BUY_CURRENCIES[$rate['code']]);
            }

            $rateSell = null;
            if (array_key_exists($rate['code'], self::SELL_CURRENCIES)) {
                $rateSell = sprintf("%.2f", $rate['mid'] + self::SELL_CURRENCIES[$rate['code']]);
            }

            $currenciesRates[] = [
                'code' => $rate['code'],
                'currency' => $rate['currency'],
                'rate_buy' => $rateBuy,
                'rate_sell' => $rateSell,
            ];
        }

        return $currenciesRates;
    }
}