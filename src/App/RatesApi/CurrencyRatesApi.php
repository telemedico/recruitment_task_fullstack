<?php

declare(strict_types=1);

namespace App\RatesApi;

use App\Entity\CurrencyRatesCollection;
use App\Processor\RateProcessor;
use App\RatesApi\Nbp\NbpCurrencyRatesApi;
use DateTimeImmutable;

class CurrencyRatesApi
{
    /**
     * @var NbpCurrencyRatesApi
     */
    private $nbpCurrencyRatesApi;
    /**
     * @var RateProcessor
     */
    private $rateProcessor;

    public function __construct(
        NbpCurrencyRatesApi $nbpCurrencyRatesApi,
        RateProcessor $rateProcessor
    ) {
        $this->nbpCurrencyRatesApi = $nbpCurrencyRatesApi;
        $this->rateProcessor = $rateProcessor;
    }

    public function get(array $currencySymbols, DateTimeImmutable $date): CurrencyRatesCollection
    {
        $tries = 0;
        do {
            if ($tries > 0) {
                $date = new DateTimeImmutable($date->format('Y-m-d') . ' -1 DAY');
            }

            $rates = $this->nbpCurrencyRatesApi->get($currencySymbols, $date);
        } while (empty($rates) && ++$tries < 5);

        $collection = new CurrencyRatesCollection([], $date);
        foreach ($rates as $rate) {
            $collection->addCurrencyRate(
                $this->rateProcessor->execute($rate)
            );
        }

        return $collection;
    }
}