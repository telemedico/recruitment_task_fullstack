<?php

declare(strict_types=1);

namespace App\Services\CurrencyDataProvider;

use App\Dtos\CurrencyCollection;
use App\Services\ExchangeRates\ExchangeRatesProviderInterface;
use App\Services\SpreadCalculator\SpreadCalculatorInterface;
use DateTime;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DefaultCurrencyDataProvider implements CurrencyDataProviderInterface
{
    private $exchangeRatesProvider;
    private $spreadCalculator;
    private $appParams;

    public function __construct(
        ExchangeRatesProviderInterface $exchangeRatesProvider,
        SpreadCalculatorInterface $spreadCalculator,
        ParameterBagInterface $parameterBag
    ) {
        $this->exchangeRatesProvider = $exchangeRatesProvider;
        $this->spreadCalculator = $spreadCalculator;
        $this->appParams = $parameterBag;
    }

    public function getData(DateTime $date): CurrencyCollection
    {
        $this->checkDate($date);
        $currencies = $this->exchangeRatesProvider->getExchangeRates($date);
        $results = new CurrencyCollection();
        $supportedCurrencies = $this->appParams->get('supportedCurrencies');

        foreach ($currencies as $currency) {
            if (in_array($currency->getCode(), $supportedCurrencies)) {
                $currency
                    ->setBuyPrice($this->spreadCalculator->buyPrice($currency))
                    ->setSellPrice($this->spreadCalculator->sellPrice($currency));

                $results->add($currency);
            }
        }

        return $results;
    }

    private function checkDate($date): void
    {
        if ($date->format("Y") < 2023) {
            throw new CurrencyDataProviderException('Wybierz datę po 2023.');
        }
    }
}