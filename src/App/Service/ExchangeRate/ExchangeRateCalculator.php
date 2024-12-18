<?php

namespace App\Service\ExchangeRate;

class ExchangeRateCalculator
{
    private const MAJOR_CURRENCIES = ['EUR', 'USD'];
    private const MAJOR_BUY_MARGIN = 0.05; // 5%
    private const MAJOR_SELL_MARGIN = 0.07; // 7%
    private const OTHER_SELL_MARGIN = 0.15; // 15%

    public function calculateRate(array $rate): array
    {
        if (!isset($rate['code'], $rate['currency'], $rate['mid'])) {
            throw new \InvalidArgumentException('Invalid rate data structure');
        }

        $code = $rate['code'];
        $midRate = (float) $rate['mid'];

        if (in_array($code, self::MAJOR_CURRENCIES)) {
            return [
                'code' => $code,
                'currency' => $rate['currency'],
                'nbpRate' => $midRate,
                'buyRate' => round($midRate * (1 - self::MAJOR_BUY_MARGIN), 4),
                'sellRate' => round($midRate * (1 + self::MAJOR_SELL_MARGIN), 4)
            ];
        }

        return [
            'code' => $code,
            'currency' => $rate['currency'],
            'nbpRate' => $midRate,
            'buyRate' => null,
            'sellRate' => round($midRate * (1 + self::OTHER_SELL_MARGIN), 4)
        ];
    }
}