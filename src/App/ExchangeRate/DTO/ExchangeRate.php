<?php

declare(strict_types = 1);

namespace App\ExchangeRate\DTO;

class ExchangeRate implements ExchangeRateInterface
{
    /**
     * @var float
     */
    private $rate;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var ?float
     */
    private $buyingRate;

    /**
     * @var ?float
     */
    private $sellingRate;

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = strtoupper($currency);
    }

    public function getBuyingRate(): ?float
    {
        return $this->buyingRate;
    }

    public function setBuyingRate(?float $buyingRate): void
    {
        $this->buyingRate = $buyingRate;
    }

    public function getSellingRate(): ?float
    {
        return $this->sellingRate;
    }

    public function setSellingRate(?float $sellingRate): void
    {
        $this->sellingRate = $sellingRate;
    }
}