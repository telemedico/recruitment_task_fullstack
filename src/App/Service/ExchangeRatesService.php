<?php
declare(strict_types=1);

namespace App\Service;
use App\Decorator\ExchangeRateDecorator;
use App\NBPApi\Client;
use App\NBPApi\DTO\ExchangesRatesDTO;

class ExchangeRatesService
{
    /** @var string[]  */
    private $rates;

    /** @var Client  */
    private $nbpApiClient;

    /** @var ExchangeRateDecorator  */
    private $decorator;

    public function __construct(Client $nbpApiClient, string $rates, ExchangeRateDecorator $decorator)
    {
        $this->nbpApiClient = $nbpApiClient;
        $this->rates = explode(',', $rates);
        $this->decorator = $decorator;
    }

    /**
     * @return ExchangesRatesDTO
     * @throws \App\NBPApi\Exceptions\InvalidDateParameter
     */
    public function provideSelectedRates(?string $date): ExchangesRatesDTO
    {
        $nbpData = $this->nbpApiClient->getExchangeRates($this->rates, $date);

        /** @todo - sorry for this arr :) */
        return $this->decorator->addValuesToRates($nbpData, [
            'USD' => [
                'sub' => 0.05,
                'add' => 0.07
            ],
            'EUR' => [
                'sub' => 0.05,
                'add' => 0.07
            ],
            '*' => [
                'sub' => 0,
                'add' => 0.15
            ]
        ]);
    }
}
