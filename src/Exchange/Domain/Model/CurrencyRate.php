<?php
namespace App\Exchange\Domain\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class CurrencyRate
{
    /**
     * @Groups("read")
     */
    private string $code;

    /**
     * @Groups("read")
     */
    private string $name;

    /**
     * @Groups("read")
     */
    private float $nbpRate;

    /**
     * @Groups("read")
     */
    private ?float $buyRate;

    /**
     * @Groups("read")
     */
    private float $sellRate;

    public function __construct(string $code, string $name, float $nbpRate, ?float $buyRate, float $sellRate)
    {
        $this->code = $code;
        $this->name = $name;
        $this->nbpRate = $nbpRate;
        $this->buyRate = $buyRate;
        $this->sellRate = $sellRate;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNbpRate(): float
    {
        return $this->nbpRate;
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
