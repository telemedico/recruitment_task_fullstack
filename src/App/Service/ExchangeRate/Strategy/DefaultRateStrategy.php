<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate\Strategy;

use App\Interfaces\ExchangeRate\RateStrategy;

class DefaultRateStrategy implements RateStrategy {
    public function getBuyRate(float $mid): ?float {
        return null; // no buying rate
    }

    public function getSellRate(float $mid): ?float {
        return round($mid + 0.15, 8);
    }
}
