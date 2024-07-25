<?php

namespace App\Exchange\Application\Service;

use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\ValueObject\CurrencyCode;
use App\Exchange\Domain\ValueObject\CurrencyName;
use App\Exchange\Domain\ValueObject\ExchangeRate;
use App\Exchange\Domain\Service\ExchangeRateCalculator;
use App\Exchange\Infrastructure\Http\ApiCurrencyRate;

class CurrencyRateFactory
{
    private ExchangeRateCalculator $exchangeRateCalculator;

    public function __construct(ExchangeRateCalculator $exchangeRateCalculator)
    {
        $this->exchangeRateCalculator = $exchangeRateCalculator;
    }
    public function create(ApiCurrencyRate $apiRate, string $currencyCode): CurrencyRate
    {
        $nbpRate = new ExchangeRate($apiRate->getRate());
        $currencyName = new CurrencyName($apiRate->getCurrency());

        $buyRate = $this->exchangeRateCalculator->calculateBuyRate($currencyCode, $nbpRate->getValue());
        $sellRate = $this->exchangeRateCalculator->calculateSellRate($currencyCode, $nbpRate->getValue());

        return new CurrencyRate(
            new CurrencyCode($currencyCode),
            $currencyName,
            $nbpRate,
            $buyRate ? new ExchangeRate($buyRate) : null,
            new ExchangeRate($sellRate)
        );
    }
}