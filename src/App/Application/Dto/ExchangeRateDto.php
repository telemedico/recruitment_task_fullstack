<?php

declare(strict_types=1);

namespace App\Application\Dto;

final class ExchangeRateDto
{
    /**
     * @var float
     */
    private $mid;

    /**
     * @var ?float
     */
    private $buyRate;

    /**
     * @var float
     */
    private $sellRate;

    public function __construct(
        float $mid,
        ?float $buyRate,
        float $sellRate
    ) {
        $this->mid = $mid;
        $this->buyRate = $buyRate;
        $this->sellRate = $sellRate;
    }

    public function getMid(): float
    {
        return $this->mid;
    }

    public function getBuyRate(): ?float
    {
        return $this->buyRate;
    }

    public function getSellRate(): float
    {
        return $this->sellRate;
    }
}
