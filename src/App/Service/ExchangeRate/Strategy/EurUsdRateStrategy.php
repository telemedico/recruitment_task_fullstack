<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate\Strategy;

use App\Interfaces\ExchangeRate\RateStrategy;

class EurUsdRateStrategy implements RateStrategy {
    public function getBuyRate(float $mid): ?float {
        return round($mid - 0.05, 8);
    }

    public function getSellRate(float $mid): ?float {
        return round($mid + 0.07, 8);
    }
}
