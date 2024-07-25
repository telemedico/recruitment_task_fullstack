<?php

declare(strict_types=1);

namespace App\Exchange\Infrastructure\Http;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ApiCurrencyRateRate
{
    /**
     * @SerializedName("no")
     * @var string
     */
    private $number;

    /**
     * @SerializedName("effectiveDate")
     * @var string
     */
    private $effectiveDate;

    /**
     * @SerializedName("mid")
     * @var float
     */
    private $mid;

    public function __construct(string $number, string $effectiveDate, float $mid)
    {
        $this->number = $number;
        $this->effectiveDate = $effectiveDate;
        $this->mid = $mid;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getEffectiveDate(): string
    {
        return $this->effectiveDate;
    }

    public function getMid(): float
    {
        return $this->mid;
    }
}
