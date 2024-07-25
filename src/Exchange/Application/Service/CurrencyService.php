<?php
namespace App\Exchange\Application\Service;

use App\Exchange\Domain\Model\CurrencyRate;
use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Exchange\Domain\Service\CurrencyServiceInterface;
use App\Exchange\Domain\Service\ExchangeRateCalculator;
use Psr\Log\LoggerInterface;

class CurrencyService implements CurrencyServiceInterface
{
    private CurrencyRateApiClientInterface $currencyRateApiClient;
    private array $currencies;
    private LoggerInterface $logger;
    private CurrencyRateFactory $currencyRateFactory;

    public function __construct(
        CurrencyRateApiClientInterface $currencyRateApiClient,
        CurrencyRateFactory            $currencyRateFactory,
        array                          $currencies,
        LoggerInterface                $logger
    )
    {
        $this->currencyRateApiClient = $currencyRateApiClient;
        $this->currencyRateFactory = $currencyRateFactory;
        $this->currencies = $currencies;
        $this->logger = $logger;
    }

    /**
     * @param string $date
     * @return CurrencyRate[]
     */
    public function getExchangeRates(string $date): array
    {
        $exchangeRates = [];

        foreach ($this->currencies as $currencyConfig) {
            $currencyCode = $currencyConfig['code'];
            try {
                $apiRate = $this->currencyRateApiClient->getExchangeRate($currencyCode, $date);
                $exchangeRates[] = $this->currencyRateFactory->create($apiRate, $currencyCode);
            } catch (\RuntimeException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $exchangeRates;
    }
}