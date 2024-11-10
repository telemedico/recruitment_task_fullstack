<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

class CurrencyRate implements JsonSerializable
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var float
     */
    private $mid;
    /**
     * @var float|null
     */
    private $buy;
    /**
     * @var float|null
     */
    private $sell;

    public function __construct(string $code, float $mid, ?float $buy, ?float $sell)
    {
        $this->code = $code;
        $this->mid = $mid;
        $this->buy = $buy;
        $this->sell = $sell;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMid(): float
    {
        return $this->mid;
    }

    public function getBuy(): ?float
    {
        return $this->buy;
    }

    public function getSell(): ?float
    {
        return $this->sell;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'mid' => $this->mid,
            'buy' => $this->buy,
            'sell' => $this->sell,
        ];
    }
}