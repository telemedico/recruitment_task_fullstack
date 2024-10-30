<?php

declare(strict_types = 1);

namespace App\ExchangeRate;

use App\ExchangeRate\DTO\ExchangeRateInterface;
use DateTime;

interface CurrencyExchangeClientInterface
{
    /**
     * @return ExchangeRateInterface[]
     */
    public function getRates(): array;

    public function setDate(DateTime $date): void;

    public function setMinDate(DateTime $date): void;

    public function addDataModifier(ExchangeRatesRequestDataModifierInterface $modifier): void;
}