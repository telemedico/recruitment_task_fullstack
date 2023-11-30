<?php

declare(strict_types=1);

namespace Currencies\Data\Currency;

class Rates implements \JsonSerializable
{
    private $sellPrice = 0;
    private $originPrice = 0;
    private $buyPrice = 0;

    public function __construct(float $originPrice, ?float $sellPrice, ?float $buyPrice) {
        $this->originPrice = $originPrice;
        $this->sellPrice = $sellPrice;
        $this->buyPrice = $buyPrice;
    }

    public function jsonSerialize()
    {
        return [
            'origin' => $this->originPrice,
            'sell' => $this->sellPrice,
            'buy' => $this->buyPrice
        ];
    }

    private function getOriginPrice(): ?float {
        return $this->sellPrice;
    }

    private function getSellPrice(): ?float {
        return $this->sellPrice;
    }

    private function getBuyPrice(): ?float {
        return $this->buyPrice;
    }
}