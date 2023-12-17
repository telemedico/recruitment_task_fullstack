<?php

declare(strict_types=1);

namespace App\Services\SpreadCalculator;

use App\Dtos\Currency;

interface SpreadCalculatorInterface
{
    public function buyPrice(Currency $currency): ?float;

    public function sellPrice(Currency $currency): ?float;
}