<?php

namespace App\Interfaces\ExchangeRate;

interface RateStrategy
{
    public function getBuyRate(float $mid): ?float;
    public function getSellRate(float $mid): ?float;
}
