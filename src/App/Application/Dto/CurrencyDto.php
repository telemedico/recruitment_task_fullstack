<?php

declare(strict_types=1);

namespace App\Application\Dto;

final class CurrencyDto
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var ExchangeRateDto
     */
    private $exchangeRate;

    public function __construct(
        string $name,
        string $code,
        ExchangeRateDto $exchangeRate
    ) {
        $this->name = $name;
        $this->code = $code;
        $this->exchangeRate = $exchangeRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getExchangeRate(): ExchangeRateDto
    {
        return $this->exchangeRate;
    }
}
