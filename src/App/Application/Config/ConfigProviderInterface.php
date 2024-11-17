<?php

declare(strict_types=1);

namespace App\Application\Config;

interface ConfigProviderInterface
{
    public function getAvailableCurrencies(): array;

    public function getBidShiftForCurrency(string $currency): float;

    public function getAskShiftForCurrency(string $currency): float;

    public function isBidAvailableForCurrency(string $currency): bool;

    public function isAskAvailableForCurrency(string $currency): bool;
}
