<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\RatesConfigProvider;
use App\Entity\CurrencyRatesCollection;
use App\Processor\RateProcessor;
use App\RatesApi\CurrencyRatesApi;
use App\RatesApi\Nbp\NbpCurrencyRatesApi;
use DateTimeImmutable;

class ExchangeRatesService
{
    /**
     * @var RatesConfigProvider
     */
    private $ratesConfigProvider;

    /**
     * @var NbpCurrencyRatesApi
     */
    private $nbpCurrencyRates;
    /**
     * @var RateProcessor
     */
    private $rateProcessor;
    /**
     * @var CurrencyRatesApi
     */
    private $currencyRatesApi;

    public function __construct(
        RatesConfigProvider $ratesConfigProvider,
        NbpCurrencyRatesApi $nbpCurrencyRates,
        CurrencyRatesApi $currencyRatesApi,
        RateProcessor $rateProcessor
    ) {
        $this->ratesConfigProvider = $ratesConfigProvider;
        $this->nbpCurrencyRates = $nbpCurrencyRates;
        $this->rateProcessor = $rateProcessor;
        $this->currencyRatesApi = $currencyRatesApi;
    }

    public function getCurrencyRates(DateTimeImmutable $date): array
    {
        $currencies = $this->ratesConfigProvider->getCurrencies();

        $chosenCollection = $this->currencyRatesApi->get($currencies, $date);
        $todayCollection = $this->currencyRatesApi->get($currencies, new DateTimeImmutable());

        $result = [];
        foreach ($currencies as $currency) {
            $singularRate = [
                'code' => $currency,
                'name' => 'TBD',
            ];

            $singularRate['chosenDate'] = $chosenCollection->getRateByCode($currency);
            $singularRate['todayDate'] = $todayCollection->getRateByCode($currency);

            $result[] = $singularRate;
        }

        return [
            'rates' => $result,
            'realChosenDate' => $chosenCollection->getDate(),
            'realTodayDate' => $todayCollection->getDate(),
        ];
    }
}