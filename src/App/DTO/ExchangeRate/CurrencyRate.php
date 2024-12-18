<?php

declare(strict_types=1);

namespace App\DTO\ExchangeRate;

final class CurrencyRate
{
    public function __construct(
        private string $code,
        private string $currency,
        private float $nbpRate,
        private ?float $buyRate,
        private float $sellRate
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getNbpRate(): float
    {
        return $this->nbpRate;
    }

    public function getBuyRate(): ?float
    {
        return $this->buyRate;
    }

    public function getSellRate(): float
    {
        return $this->sellRate;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'currency' => $this->currency,
            'nbpRate' => $this->nbpRate,
            'buyRate' => $this->buyRate,
            'sellRate' => $this->sellRate
        ];
    }
}