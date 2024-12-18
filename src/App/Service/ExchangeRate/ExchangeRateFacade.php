<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\Interface\ExchangeRate\ExchangeRateProviderInterface;

final class ExchangeRateFacade
{
    public function __construct(
        private readonly ExchangeRateProviderInterface $rateProvider,
        private readonly ExchangeRateCalculator $calculator
    ) {}

    public function getExchangeRates(\DateTimeInterface $date): array
    {
        $rates = $this->rateProvider->getRatesForDate($date);
        
        return array_map(
            fn (array $rate) => $this->calculator->calculateRate($rate)->toArray(),
            $rates
        );
    }
}