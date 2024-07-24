<?php
namespace App\Exchange\Application\Service;

use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Exchange\Domain\Service\CurrencyServiceInterface;
use App\Exchange\Domain\Service\ExchangeRateCalculator;


class CurrencyService implements CurrencyServiceInterface
{
    private CurrencyRateApiClientInterface $currencyRateApiClient;
    private ExchangeRateCalculator $calculator;
    private array $currencies;

    public function __construct(
        CurrencyRateApiClientInterface $currencyRateApiClient,
        ExchangeRateCalculator $calculator,
        array $currencies
    ) {
        $this->currencyRateApiClient = $currencyRateApiClient;
        $this->calculator = $calculator;
        $this->currencies = $currencies;
    }

    public function getExchangeRates(string $date): array
    {
        $exchangeRates = [];

        foreach ($this->currencies as $currencyConfig) {
            $currencyCode = $currencyConfig['code'];
            $rateData = $this->currencyRateApiClient->getExchangeRate($currencyCode, $date);
            $nbpRate = $rateData['rates'][0]['mid'];
            $currencyName = $rateData['currency'];

            $buyRate = $this->calculator->calculateBuyRate($currencyCode, $nbpRate);
            $sellRate = $this->calculator->calculateSellRate($currencyCode, $nbpRate);

            $exchangeRates[] = new CurrencyRate($currencyCode, $currencyName, $nbpRate, $buyRate, $sellRate);
        }

        return $exchangeRates;
    }
}
