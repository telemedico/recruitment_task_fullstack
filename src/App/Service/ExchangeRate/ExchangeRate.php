<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\Interfaces\ExchangeRate\RateStrategy;

class ExchangeRate {
    private $currency;
    private $code;
    private $mid;
    private $rateStrategy;

    public function __construct(string $currency, string $code, float $mid, RateStrategy $rateStrategy) {
        $this->currency = $currency;
        $this->code = $code;
        $this->mid = $mid;
        $this->rateStrategy = $rateStrategy;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getMid(): float {
        return $this->mid;
    }

    public function getBuyRate(): ?float {
        return $this->rateStrategy->getBuyRate($this->mid);
    }

    public function getSellRate(): float {
        return $this->rateStrategy->getSellRate($this->mid);
    }
}
