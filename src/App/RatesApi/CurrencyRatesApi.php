<?php

declare(strict_types=1);

namespace App\RatesApi;

use App\Entity\CurrencyRatesCollection;
use App\Processor\RateProcessor;
use App\RatesApi\ApiProvider\CurrencyRatesApiInterface;
use DateTimeImmutable;

/**
 * todo: Idea for future - use more than one api, to have fallback (use f.e. chain of responsibility)
 */
class CurrencyRatesApi
{
    /**
     * @var CurrencyRatesApiInterface
     */
    private $api;
    /**
     * @var RateProcessor
     */
    private $rateProcessor;

    public function __construct(
        CurrencyRatesApiInterface $api,
        RateProcessor $rateProcessor
    ) {
        $this->api = $api;
        $this->rateProcessor = $rateProcessor;
    }

    public function get(array $currencySymbols, DateTimeImmutable $date): CurrencyRatesCollection
    {
        $tries = 0;
        do {
            if ($tries > 0) {
                $date = new DateTimeImmutable($date->format('Y-m-d') . ' -1 DAY');
            }

            $rates = $this->api->get($currencySymbols, $date);
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