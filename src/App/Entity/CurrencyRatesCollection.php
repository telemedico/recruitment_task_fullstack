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

    /**
     * @param CurrencyRate[] $currencyRates
     * @param DateTimeImmutable $date
     */
    public function __construct(array $currencyRates, DateTimeImmutable $date)
    {
        $this->currencyRates = $currencyRates;
        $this->date = $date;
    }

    public function addCurrencyRate(CurrencyRate $currencyRate): void
    {
        $this->currencyRates[] = $currencyRate;
    }

    /**
     * @return CurrencyRate[]
     */
    public function getCurrencyRates(): array
    {
        return $this->currencyRates;
    }

    public function getRateByCode(string $code): ?CurrencyRate
    {
        foreach ($this->currencyRates as $rate) {
            if ($rate->getCode() === $code) {
                return $rate;
            }
        }

        return null;
    }

    public function getDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->date,
            'currencyRates' => $this->currencyRates,
        ];
    }
}