<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use JsonSerializable;

class CurrencyRatesCollection implements JsonSerializable
{
    /**
     * @var array
     */
    private $currencyRates;
    /**
     * @var DateTimeImmutable
     */
    private $date;

    public function __construct(array $currencyRates, DateTimeImmutable $date)
    {
        $this->currencyRates = $currencyRates;
        $this->date = $date;
    }

    public function addCurrencyRate(CurrencyRate $currencyRate): void
    {
        $this->currencyRates[] = $currencyRate;
    }

    public function getCurrencyRates(): array
    {
        return $this->currencyRates;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->date,
            'currencyRates' => $this->currencyRates,
        ];
    }
}