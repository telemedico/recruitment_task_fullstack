<?php

declare(strict_types=1);

namespace App\Exchange\Infrastructure\Http;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ApiCurrencyRate
{
    /**
     * @SerializedName("currency")
     * @var string
     */
    private $currency;

    /**
     * @SerializedName("code")
     * @var string
     */
    private $code;

    /**
     * @SerializedName("rates")
     * @var ApiCurrencyRateRate[]
     */
    private $rates;

    /**
     * @param ApiCurrencyRateRate[] $rates
     */
    public function __construct(string $currency, string $code, array $rates)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->rates = $rates;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return ApiCurrencyRateRate[]
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    public function getRate(): float
    {
        return $this->rates[0]->getMid();
    }
}
