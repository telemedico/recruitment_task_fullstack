<?php

declare(strict_types=1);

namespace App\Exchange\Application\Service;

use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\ExchangeRateCalculator;
use App\Exchange\Domain\ValueObject\CurrencyCode;
use App\Exchange\Domain\ValueObject\CurrencyName;
use App\Exchange\Domain\ValueObject\ExchangeRate;
use App\Exchange\Infrastructure\Http\ApiCurrencyRate;

class CurrencyRateFactory
{
    /**
     * @var ExchangeRateCalculator
     */
    private $exchangeRateCalculator;

    public function __construct(ExchangeRateCalculator $exchangeRateCalculator)
    {
        $this->exchangeRateCalculator = $exchangeRateCalculator;
    }

    public function create(ApiCurrencyRate $apiRate): CurrencyRate
    {
        $currencyCode = new CurrencyCode($apiRate->getCode());
        $currencyName = new CurrencyName($apiRate->getCurrency());
        $nbpRate = new ExchangeRate($apiRate->getRates()[0]->getMid());

        $buyRate = $this->exchangeRateCalculator->calculateBuyRate($currencyCode->getValue(), $nbpRate->getValue());
        $sellRate = $this->exchangeRateCalculator->calculateSellRate($currencyCode->getValue(), $nbpRate->getValue());

        return new CurrencyRate(
            $currencyCode,
            $currencyName,
            $nbpRate,
            $buyRate ? new ExchangeRate($buyRate) : null,
            new ExchangeRate($sellRate)
        );
    }
}
