<?php

declare(strict_types=1);

namespace App\Query;

use JsonSerializable;

final class CurrencyPricesDto implements JsonSerializable
{
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string|null
     */
    private $buyPrice;
    /**
     * @var string|null
     */
    private $sellPrice;

    public function __construct(string $currency, string $code, ?string $buyPrice, ?string $sellPrice)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->buyPrice = $buyPrice;
        $this->sellPrice = $sellPrice;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function buyPrice(): ?string
    {
        return $this->buyPrice;
    }

    public function sellPrice(): ?string
    {
        return $this->sellPrice;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
