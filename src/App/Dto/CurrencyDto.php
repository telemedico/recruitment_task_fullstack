<?php


namespace App\Dto;

use JsonSerializable;

class CurrencyDto implements JsonSerializable
{
    private $currency;
    private $code;
    private $mid;
    private $purchase;
    private $sale;

    public function __construct(string $currency, string $code, string $mid, string $purchase, string $sale)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->mid = $mid;
        $this->sale = $sale;
        $this->purchase = $purchase;
    }

    /**
     * @return float
     */
    public function getPurchase(): string
    {
        return $this->purchase;
    }

    /**
     * @param float $purchase
     */
    public function setPurchase(string $purchase): void
    {
        $this->purchase = $purchase;
    }

    /**
     * @return float
     */
    public function getSale(): string
    {
        return $this->sale;
    }

    /**
     * @param float $sale
     */
    public function setSale(string $sale): void
    {
        $this->sale = $sale;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMid(): string
    {
        return $this->mid;
    }

    public function jsonSerialize(): array
    {
        return [
            'currency' => $this->currency,
            'code' => $this->code,
            'mid' => $this->mid,
            'purchase' => $this->purchase,
            'sale' => $this->sale,
        ];
    }
}

