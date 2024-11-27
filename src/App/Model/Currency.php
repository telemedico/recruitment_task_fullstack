<?php

namespace App\Model;

class Currency
{
    /** @var string $code */
    private $code;
    /** @var null|float $commissionAdd */
    private $commissionAdd;
    /** @var null|float $commissionRemove */
    private $commissionRemove;

    public function __construct(
        string $code,
        ?float $commissionAdd,
        ?float $commissionRemove = null
    ) {
        $this->code = $code;
        $this->commissionAdd = $commissionAdd;
        $this->commissionRemove = $commissionRemove;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCommissionRemove(): ?float
    {
        return $this->commissionRemove;
    }

    public function getCommissionAdd(): ?float
    {
        return $this->commissionAdd;
    }
}