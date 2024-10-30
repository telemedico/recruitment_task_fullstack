<?php

declare(strict_types = 1);

namespace App\ExchangeRate\DTO;

use DateTime;

interface ExchangeRateInterface
{
    public function getRate(): float;

    public function setRate(float $rate);

    public function getCurrency(): string;

    public function setCurrency(string $currency);

    public function getBuyingRate(): ?float;

    public function setBuyingRate(?float $buyingRate): void;

    public function getSellingRate(): ?float;

    public function setSellingRate(?float $sellingRate): void;
}