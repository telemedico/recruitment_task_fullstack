<?php

declare(strict_types=1);

namespace App\Dto;

class CurrencyRateDto
{
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $name;
    /**
     * @var float
     */
    private $buy;
    /**
     * @var float
     */
    private $sell;

    public function __construct(
        string $currency,
        string $name,
        ?float $buy,
        ?float $sell
    )
    {
        $this->currency = $currency;
        $this->name = $name;
        $this->buy = $buy;
        $this->sell = $sell;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBuy(): ?float
    {
        return $this->buy;
    }

    public function getSell(): ?float
    {
        return $this->sell;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'name' => $this->name,
            'buy' => $this->buy,
            'sell' => $this->sell,
        ];
    }
}