<?php

declare(strict_types=1);

namespace App\Exchange\Domain\Service;

use App\Exchange\Domain\Model\CurrencyRate;

interface CurrencyServiceInterface
{
    /**
     * Get exchange rates for a specific date.
     *
     * @return CurrencyRate[]
     */
    public function getExchangeRates(\DateTimeImmutable $date): array;
}
