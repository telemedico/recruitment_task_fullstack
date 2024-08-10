<?php

declare(strict_types=1);

namespace App\Query;

use App\Gateway\AverageExchangeRatesGateway;
use App\Service\CurrencySpread;

final class CurrencyPricesQuery
{
    /**
     * @var AverageExchangeRatesGateway
     */
    private $averageExchangeRatesGateway;
    /**
     * @var CurrencySpread
     */
    private $currencySpreadConfig;

    public function __construct(
        CurrencySpread $currencySpreadConfig,
        AverageExchangeRatesGateway $averageExchangeRatesGateway
    ) {
        $this->currencySpreadConfig = $currencySpreadConfig;
        $this->averageExchangeRatesGateway = $averageExchangeRatesGateway;
    }

    /**
     * @return array<CurrencyPricesDto>
     */
    public function fetchAllForDateTime(\DateTimeImmutable $dateTime): array
    {
        $averageExchangeRates = $this->averageExchangeRatesGateway
            ->fetchRates($dateTime, ...$this->currencySpreadConfig->supportedCurrencies());

        $currencyPrices = [];
        foreach ($averageExchangeRates as $averageExchangeRate) {
            $currencyPrices[] = new CurrencyPricesDto(
                $averageExchangeRate->currency(),
                $averageExchangeRate->code(),
                $this->currencySpreadConfig->calculateBuyPrice(
                    $averageExchangeRate->code(),
                    $averageExchangeRate->mid()
                ),
                $this->currencySpreadConfig->calculateSellPrice(
                    $averageExchangeRate->code(),
                    $averageExchangeRate->mid()
                )
            );
        }

        return $currencyPrices;
    }
}
