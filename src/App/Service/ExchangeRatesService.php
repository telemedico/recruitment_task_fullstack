<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class ExchangeRatesService
{
    const SCALE = 10;
    const BUY_DIFF_DEFAULT = '';
    const SELL_DIFF_DEFAULT = '0.15';
    const CURRENCIES = [
        'EUR' => ['buydiff' => '-0.05', 'selldiff' => '0.07'],
        'USD' => ['buydiff' => '-0.05', 'selldiff' => '0.07'],
        'CZK' => [],
        'IDR' => [],
        'BRL' => [],
    ];

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function getRates(&$exchangeRates, $exchangeRatesDate = '')
    {
        $exchangeRatesDateS = '';
        if ($exchangeRatesDate) {
            $exchangeRatesDateS = "$exchangeRatesDate/";
        }
        $resp = $this->client->request(
            'GET',
            "https://api.nbp.pl/api/exchangerates/tables/A/$exchangeRatesDateS?format=json"
        );
        $resp = $resp->toArray()[0];
        $rates = $resp['rates'];
        foreach ($rates as $key => &$value) {
            $code = $value['code'];
            if (!isset(self::CURRENCIES[$code])) {
                unset($rates[$key]);
                continue;
            }
            $buydiff = isset(self::CURRENCIES[$code]['buydiff']) ? self::CURRENCIES[$code]['buydiff'] : self::BUY_DIFF_DEFAULT;
            $selldiff = isset(self::CURRENCIES[$code]['selldiff']) ? self::CURRENCIES[$code]['selldiff'] : self::SELL_DIFF_DEFAULT;
            $value['buy'] = rtrim(bcadd($value['mid'], $buydiff, self::SCALE), '0');
            $value['sell'] = rtrim(bcadd($value['mid'], $selldiff, self::SCALE), '0');
        }
        $exchangeRates[$exchangeRatesDate ? 'fromDate' : 'recent'] = [
            'rates' => array_values($rates),
            'effectiveDate' => $exchangeRatesDate ?: $resp['effectiveDate'],
        ];
    }

    public function getExchangeRates($exchangeRatesDate = '', $getRecent = true)
    {
        $exchangeRates = [
            'recent' => [
                'rates' => [],
                'effectiveDate' => '',
            ],
            'fromDate' => [
                'rates' => [],
                'effectiveDate' => '',
            ],
        ];
        if ($getRecent) {
            try {
                $this->getRates($exchangeRates);
            } catch (Throwable $e) {
                $exchangeRates['recent']['err'] = $e->getMessage();
            }
        }
        if ($exchangeRatesDate) {
            try {
                $this->getRates($exchangeRates, $exchangeRatesDate);
            } catch (Throwable $e) {
                $exchangeRates['fromDate']['err'] = $e->getMessage();
            }
        }
        return $exchangeRates;
    }
}
