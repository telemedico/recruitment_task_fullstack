<?php

declare(strict_types=1);

namespace App\Exchange\Application\Service;

use App\Exchange\Application\Exception\NoExchangeRatesFoundException;
use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Exchange\Domain\Service\CurrencyServiceInterface;
use App\Shared\Modules\RestClient\Exceptions\RestClientResponseException;

class CurrencyService implements CurrencyServiceInterface
{
    private $currencyRateApiClient;
    private $currencyRateFactory;
    private $currencies;

    public function __construct(
        CurrencyRateApiClientInterface $currencyRateApiClient,
        CurrencyRateFactory $currencyRateFactory,
        array $currencies
    ) {
        $this->currencyRateApiClient = $currencyRateApiClient;
        $this->currencyRateFactory = $currencyRateFactory;
        $this->currencies = $currencies;
    }

    /**
     * @return CurrencyRate[]
     * @throws NoExchangeRatesFoundException
     */
    public function getExchangeRates(\DateTimeImmutable $date): array
    {
        $exchangeRates = [];

        foreach ($this->currencies as $currencyConfig) {
            $currencyCode = $currencyConfig['code'];
            try {
                $apiRate = $this->currencyRateApiClient->getExchangeRate($currencyCode, $date);
                $exchangeRates[] = $this->currencyRateFactory->create($apiRate);
            } catch (RestClientResponseException $e) {
                if (404 == $e->getCode()) {
                    throw new NoExchangeRatesFoundException('No exchange rates found for the given date.');
                }

                throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $exchangeRates;
    }
}
