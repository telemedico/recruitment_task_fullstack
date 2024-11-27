<?php

namespace App\ViewModel;

use JsonSerializable;

class CurrencyPriceViewModel implements JsonSerializable
{
    /** @var string $code */
    private $code;
    /** @var ?string $name */
    private $name;
    /** @var ?float $buyPrice */
    private $buyPrice;
    /** @var ?float $sellPrice */
    private $sellPrice;
    /** @var ?float $nbpPrice */
    private $nbpPrice;

    public function __construct(
        string $code,
        ?string $name,
        ?float $buyPrice,
        ?float $sellPrice,
        ?float $nbpPrice
    ) {
            $this->code = $code;
            $this->name = $name;
            $this->buyPrice = $buyPrice;
            $this->sellPrice = $sellPrice;
            $this->nbpPrice = $nbpPrice;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBuyPrice(): ?float
    {
        return $this->buyPrice;
    }

    public function getSellPrice(): ?float
    {
        return $this->sellPrice;
    }

    public function getNbpPrice(): ?float
    {
        return $this->nbpPrice;
    }
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'buyPrice' => $this->getBuyPrice(),
            'sellPrice' => $this->getSellPrice(),
            'nbpPrice' => $this->getNbpPrice()
        ];
    }
}