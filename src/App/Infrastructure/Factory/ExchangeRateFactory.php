<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Currency;
use App\Domain\ExchangeRate;

final class ExchangeRateFactory
{
    /**
     * @param array{"currency": string, "code": string, "mid": float} $data
     */
    public function buildFromArray(array $data): ExchangeRate
    {
        return new ExchangeRate(
            new Currency(
                $data['code'],
                $data['currency']
            ),
            (float) $data['mid']
        );
    }
}
