<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\RatesConfigProvider;
use App\Processor\RateProcessor;
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

    public function __construct(
        RatesConfigProvider $ratesConfigProvider,
        NbpCurrencyRatesApi $nbpCurrencyRates,
        RateProcessor $rateProcessor
    ) {
        $this->ratesConfigProvider = $ratesConfigProvider;
        $this->nbpCurrencyRates = $nbpCurrencyRates;
        $this->rateProcessor = $rateProcessor;
    }

    public function getAllCurrencyRates(DateTimeImmutable $date): array
    {
        $currencies = $this->ratesConfigProvider->getCurrencies();

        $rates = $this->nbpCurrencyRates->get($currencies, $date);
        $ratesToday = $this->nbpCurrencyRates->get($currencies, new DateTimeImmutable(), true);

        $result = [];
        foreach ($currencies as $currency) {
            $singularRate = [
                'code' => $currency,
                'name' => 'TBD',
            ];

            foreach ($rates as $rate) {
                if ($rate['code'] !== $currency) {
                    continue;
                }

                $singularRate['chosenDate'] = $this->rateProcessor->execute($rate);
                break;
            }

            foreach ($ratesToday as $rate) {
                if ($rate['code'] !== $currency) {
                    continue;
                }

                $singularRate['todayDate'] = $this->rateProcessor->execute($rate);
                break;
            }

            $result[] = $singularRate;
        }

        return $result;
    }
}