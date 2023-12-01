<?php

declare(strict_types=1);

namespace App\Service;

define('code', 'code');
define('effectiveDate', 'effectiveDate');
define('sellCommision', 'sellCommision');
define('buyCommision', 'buyCommision');
define('multiplied', 'multiplied');
define('_default', '_default');
define('currencies', 'currencies');
define('onlyLatestData', 'onlyLatestData');
define('latest', 'latest');
define('rates', 'rates');
define('historical', 'historical');
define('latestDate', 'latestDate');

class ExchangeCurrencyDataModelConverter {
    private Array $showCurrencies = ['EUR', 'USD', 'CZK', 'IDR', 'BRL', 'SLO'];
    private Array $currencyExchangeParams = [
        'EUR' => [
            sellCommision => 0.05,
            buyCommision => 0.05,
            multiplied => 1,
        ],
        'USD' => [
            sellCommision => 0.05,
            buyCommision => 0.05,
            multiplied => 1,
        ],
        'IDR' => [
            sellCommision => 0.15,
            buyCommision => null,
            multiplied => 1000,
        ],
        _default => [
            sellCommision => 0.15,
            buyCommision => null,
            multiplied => 1,
        ]
    ];

   
    public function calculateExchangeDataModel(Array $response, bool $onlyLatestData): Array {
        $result = $this->calculateDates($response);
        $result[onlyLatestData] = $onlyLatestData;
        $result[currencies] = $onlyLatestData
            ? $this->calculateExchangeDataModelLatestOnly($response)
            : $this->calculateExchangeDataModelWithHistorical($response);
        return $result;
    }

    private function calculateExchangeDataModelLatestOnly(array $response): Array {

        $toCalculateMain = $response[latest][rates];
        $result = [];

        foreach($toCalculateMain as $item) {
            $code = $item[code];
            if (in_array($code, $this->showCurrencies)) {
                $result[] = $this->calculateCurrencyPrices(
                    true, $item, null
                );
            }
        }
        return $result;
    }

    private function calculateExchangeDataModelWithHistorical(array $response): Array {
        $result = [];
        $arrLatest = $response[latest][rates];
        $arrHistorical = $response[historical][rates];
        $sizeLatest = sizeof($arrLatest);
        $sizeHistorical = sizeof($arrHistorical);
        $max = max($sizeLatest, $sizeHistorical);
        $toCalculateLatest = [];
        $toCalculateMain = [];

        for($i = 0; $i < $max; $i++) {
            if ($i < $sizeLatest) {
                $item = $arrLatest[$i];
                $code = $item[code];
                if (in_array($code, $this->showCurrencies)) {
                    $toCalculateLatest[$code] = $item;
                }
            }
            if ($i < $sizeHistorical) {
                $item = $arrHistorical[$i];
                $code = $item[code];
                if (in_array($code, $this->showCurrencies)) {
                    $toCalculateMain[$code] = $item;
                }
            }
        }
        
        foreach($toCalculateMain as $item) {
            $code = $item[code];
            $result[] = $this->calculateCurrencyPrices(
                false, $item, is_array($toCalculateLatest[$code]) ? $toCalculateLatest[$code]['mid'] : null
            );
        }
        return $result;
    }

    private function calculateDates(Array $response): Array {
        $result = [
            effectiveDate => $response[onlyLatestData] 
                ? $response[latest][effectiveDate] 
                : $response[historical][effectiveDate] 
        ];
        if (!$response[onlyLatestData]) {
            $result[latestDate] = $response[latest][effectiveDate];
        }
        return $result;
    }

    private function getAmountMultiplied(string $currencyCode): int  {
        return array_key_exists($currencyCode, $this->currencyExchangeParams) 
            ? $this->currencyExchangeParams[$currencyCode][multiplied]
            : $this->currencyExchangeParams[_default][multiplied];
    }

    private function getSellCommision(string $currencyCode): Float|null  {
        return array_key_exists($currencyCode, $this->currencyExchangeParams) 
            ? $this->currencyExchangeParams[$currencyCode][sellCommision]
            : $this->currencyExchangeParams[_default][sellCommision];
    }

    private function getBuyCommision(string $currencyCode): Float|null  {
        return array_key_exists($currencyCode, $this->currencyExchangeParams) 
            ? $this->currencyExchangeParams[$currencyCode][buyCommision]
            : $this->currencyExchangeParams[_default][buyCommision];
    }
    
    private function calculateCurrencyPrices(bool $onlyLatestData, Array $item, Float|null $currentMid): Array {
        $amountMultiplied = $this->getAmountMultiplied($item[code]);
        $buyCommision =  $this->getBuyCommision($item[code]);
        $sellCommision = $this->getSellCommision($item[code]);
        $multipliedMid = $item['mid'] * $amountMultiplied;
        $result = array_merge(
            $item,
            [
                'nbp'=> $multipliedMid,
                'buy'=> is_float($buyCommision) ? $multipliedMid + $buyCommision : null,
                'sell'=> is_float($sellCommision) ? $multipliedMid + $sellCommision : null,
                'key'=> $item[code],
                'amountMultiplied' => $amountMultiplied
            ]
        );
        if (!$onlyLatestData && is_float($currentMid)) {
            $multipliedCurrentMid = $currentMid * $amountMultiplied;
            $result['currentMid'] = $currentMid;
            $result['currentNbp'] = $multipliedCurrentMid;
            $result['currentBuy'] =  is_float($buyCommision) ? $multipliedCurrentMid + $buyCommision : null;
            $result['currentSell'] = is_float($sellCommision) ? $multipliedCurrentMid + $sellCommision : null;
        }
        return $result;
    }
}