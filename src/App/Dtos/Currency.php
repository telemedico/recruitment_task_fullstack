<?php

declare(strict_types=1);

namespace App\Dtos;

class Currency
{
    private $name;
    private $code;
    private $price;
    private $buyPrice;
    private $sellPrice;

    public function setName(string $currency): Currency
    {
        $this->name = $currency;

        return $this;
    }

    public function setCode(string $code): Currency
    {
        $this->code = $code;

        return $this;
    }

    public function setPrice(float $price): Currency
    {
        $this->price = $price;

        return $this;
    }

    public function setBuyPrice($buyPrice): Currency
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function setSellPrice($sellPrice): Currency
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getBuyPrice(): ?float
    {
        return $this->buyPrice;
    }

    public function getSellPrice(): ?float
    {
        return $this->sellPrice;
    }
}