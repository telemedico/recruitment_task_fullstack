<?php
namespace App\Exchange\Infrastructure\Http;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ApiCurrencyRate
{
    /**
     * @SerializedName("currency")
     */
    private string $currency;

    /**
     * @SerializedName("code")
     */
    private string $code;

    /**
     * @SerializedName("rates")
     * @var ApiCurrencyRateRate[]
     */
    private array $rates;

    /**
     * @param string $currency
     * @param string $code
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
