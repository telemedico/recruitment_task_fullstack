<?php

namespace App\Exchange\Domain\Model;

use App\Exchange\Domain\ValueObject\CurrencyCode;
use App\Exchange\Domain\ValueObject\CurrencyName;
use App\Exchange\Domain\ValueObject\ExchangeRate;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class CurrencyRate
{
    /**
     * @Groups("write")
     */
    private CurrencyCode $code;

    /**
     * @Groups("write")
     */
    private CurrencyName $name;

    /**
     * @Groups("write")
     */
    private ExchangeRate $nbpRate;

    /**
     * @Groups("write")
     */
    private ?ExchangeRate $buyRate;

    /**
     * @Groups("write")
     */
    private ExchangeRate $sellRate;

    public function __construct(
        CurrencyCode  $code,
        CurrencyName  $name,
        ExchangeRate  $nbpRate,
        ?ExchangeRate $buyRate,
        ExchangeRate  $sellRate
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->nbpRate = $nbpRate;
        $this->buyRate = $buyRate;
        $this->sellRate = $sellRate;
    }

    public function getCode(): CurrencyCode
    {
        return $this->code;
    }

    public function getName(): CurrencyName
    {
        return $this->name;
    }

    public function getNbpRate(): ExchangeRate
    {
        return $this->nbpRate;
    }

    public function getBuyRate(): ?ExchangeRate
    {
        return $this->buyRate;
    }

    public function getSellRate(): ExchangeRate
    {
        return $this->sellRate;
    }

    public function setBuyRate(?ExchangeRate $buyRate): void
    {
        $this->buyRate = $buyRate;
    }

    public function setSellRate(ExchangeRate $sellRate): void
    {
        $this->sellRate = $sellRate;
    }

    /**
     * @Groups("read")
     * @SerializedName("code")
    */
    public function getFlatCode(): string
    {
        return (string)$this->code;
    }

    /**
     * @Groups("read")
     * @SerializedName("name")
     */
    public function getFlatName(): string
    {
        return (string)$this->name;
    }

    /**
     * @Groups("read")
     * @SerializedName("nbpRate")
     */
    public function getFlatNbpRate(): float
    {
        return $this->nbpRate->getValue();
    }

    /**
     * @Groups("read")
     * @SerializedName("buyRate")
     */
    public function getFlatBuyRate(): ?float
    {
        return $this->buyRate ? $this->buyRate->getValue() : null;
    }

    /**
     * @Groups("read")
     * @SerializedName("sellRate")
     */
    public function getFlatSellRate(): float
    {
        return $this->sellRate->getValue();
    }
}
