<?php

namespace App\Model;

use DateTime;

class CurrencyValue
{
    /** @var string $code */
    private $code;
    /** @var string $name */
    private $name;
    /** @var DateTime $effectiveDate */
    private $effectiveDate;
    /** @var float $price */
    private $price;
    public function __construct(
        string $code,
        string $name,
        DateTime $effectiveDate,
        float $price
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->effectiveDate = $effectiveDate;
        $this->price = $price;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEffectiveDate(): DateTime
    {
        return $this->effectiveDate;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}