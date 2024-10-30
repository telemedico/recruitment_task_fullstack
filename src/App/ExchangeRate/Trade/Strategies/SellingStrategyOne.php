<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Trade\Strategies;

use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\Trade\CurrencyTradeRateCalculationInterface;

/**
 * Nie mialem pomyslu na nazewnictwo w tym przypadku, pewnie jakbym byl blizej biznesu i konkretnej domeny to wiedzialbym
 * ze np. te  -0.07 etc. dla EUR i USD i innych walut ma jakies okreslenie
 */
class SellingStrategyOne implements CurrencyTradeRateCalculationInterface
{
    public function calculate(ExchangeRateInterface $rate): ?float
    {
        return round($rate->getRate() + 0.07, 4);
    }
}