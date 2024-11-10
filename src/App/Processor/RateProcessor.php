<?php

declare(strict_types=1);

namespace App\Processor;

use App\Config\RatesConfigProvider;

class RateProcessor
{
    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    public function __construct(RatesConfigProvider $ratesConfigProvider)
    {
        $this->ratesConfigProvider = $ratesConfigProvider;
    }

    public function execute(array $rate): array
    {
        $processed = [
            'mid' => $rate['mid'],
            'buy' => $this->getBuyForRate($rate['code'], $rate['mid']),
            'sell' => $this->getSellForRate($rate['code'], $rate['mid']),
        ];

        return $processed;
    }

    private function getBuyForRate(string $code, float $mid): ?float
    {
        foreach ($this->ratesConfigProvider->getRelativeRates() as $relativeRate) {
            if (!in_array($code, $relativeRate['currencies'])) {
                continue;
            }

            if (null === $relativeRate['buy']) {
                return null;
            }

            return $mid + $relativeRate['buy'];
        }
    }

    private function getSellForRate(string $code, float $mid): ?float
    {
        foreach ($this->ratesConfigProvider->getRelativeRates() as $relativeRate) {
            if (!in_array($code, $relativeRate['currencies'])) {
                continue;
            }

            if (null === $relativeRate['sell']) {
                return null;
            }

            return $mid + $relativeRate['sell'];
        }
    }
}