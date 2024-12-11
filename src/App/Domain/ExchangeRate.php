<?php

declare(strict_types=1);

namespace App\Domain;

final class ExchangeRate
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var float
     */
    private $midRate;

    public function __construct(
        Currency $currency,
        float $midRate
    ) {
        $this->currency = $currency;
        $this->midRate = $midRate;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getMidRate(): float
    {
        return $this->midRate;
    }

    public function getBuyRate(): ?float
    {
        switch ($this->currency->getCode()) {
            case Currency::EUR:
            case Currency::USD:
                return $this->midRate - 0.05;
            default:
                return null;
        }
    }

    public function getSellRate(): float
    {
        switch ($this->currency->getCode()) {
            case Currency::EUR:
            case Currency::USD:
                return $this->midRate + 0.07;
            default:
                return $this->midRate + 0.15;
        }
    }
}
