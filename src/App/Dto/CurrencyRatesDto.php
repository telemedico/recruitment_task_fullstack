<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeInterface;

class CurrencyRatesDto
{
    /**
     * @var DateTimeInterface
     */
    private $date;
    /**
     * @var array
     */
    private $rates;

    public function __construct(DateTimeInterface $date, array $rates)
    {
        $this->date = $date;
        $this->rates = $rates;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}