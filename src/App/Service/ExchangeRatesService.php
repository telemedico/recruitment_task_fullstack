<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\RatesConfigProvider;
use App\RatesApi\CurrencyRatesApi;
use DateTimeImmutable;

class ExchangeRatesService
{
    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    /**
     * @var CurrencyRatesApi
     */
    private $currencyRatesApi;

    public function __construct(
        RatesConfigProvider $ratesConfigProvider,
        CurrencyRatesApi $currencyRatesApi
    ) {
        $this->ratesConfigProvider = $ratesConfigProvider;
        $this->currencyRatesApi = $currencyRatesApi;
    }

    public function getCurrencyRates(DateTimeImmutable $date): array
    {
        $currencies = $this->ratesConfigProvider->getCurrencyCodes();
        $currenciesToNames = $this->ratesConfigProvider->getCurrenciesAndNames();

        $chosenCollection = $this->currencyRatesApi->get($currencies, $date);
        $todayCollection = $this->currencyRatesApi->get($currencies, new DateTimeImmutable());

        $result = [];
        foreach ($currenciesToNames as $currencyCode => $currencyName) {
            $singularRate = [
                'code' => $currencyCode,
                'name' => $currencyName,
            ];

            $singularRate['chosenDate'] = $chosenCollection->getRateByCode($currencyCode);
            $singularRate['todayDate'] = $todayCollection->getRateByCode($currencyCode);

            $result[] = $singularRate;
        }

        return [
            'rates' => $result,
            'realChosenDate' => $chosenCollection->getDate(),
            'realTodayDate' => $todayCollection->getDate(),
        ];
    }
}