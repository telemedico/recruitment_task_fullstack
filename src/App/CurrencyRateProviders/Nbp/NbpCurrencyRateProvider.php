<?php

declare(strict_types=1);

namespace App\CurrencyRateProviders\Nbp;

use App\CurrencyRateProviders\CurrencyRateProviderInterface;
use App\Dto\CurrencyRatesDto;
use App\Enum\CurrencyEnum;
use App\Service\CurrencyRateCalculator;
use DateTime;

class NbpCurrencyRateProvider implements CurrencyRateProviderInterface
{
    /**
     * @var NbpCurrencyRateClient
     */
    private $client;

    /**
     * @var CurrencyRateCalculator
     */
    private $currencyRateCalculator;

    public function __construct(
        NbpCurrencyRateClient  $client,
        CurrencyRateCalculator $currencyRateCalculator
    )
    {
        $this->client = $client;
        $this->currencyRateCalculator = $currencyRateCalculator;
    }

    public function getCurrencyRates(?DateTime $dateTime = null): CurrencyRatesDto
    {
        $response = $this->client->getCurrencyRates($dateTime);

        $currencyRates = array_filter($response['rates'], function (array $rate) {
            return CurrencyEnum::supports($rate['code']);
        });

        $currencyRates = array_map(function (array $rate) {
            return ($this->currencyRateCalculator)(
                $rate['code'],
                $rate['currency'],
                $rate['mid']
            );
        }, $currencyRates);

        return new CurrencyRatesDto(
            new DateTime($response['effectiveDate']),
            array_values($currencyRates)
        );
    }
}