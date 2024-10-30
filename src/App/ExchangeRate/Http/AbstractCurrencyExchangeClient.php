<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Http;

use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\ExchangeRatesRequestDataModifierInterface;
use App\ExchangeRate\UsedCurrenciesProvider;
use DateTime;

class AbstractCurrencyExchangeClient
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var DateTime
     */
    protected $minDate;

    /**
     * @var ExchangeRatesRequestDataModifierInterface[]
     */
    protected $dataModifiers;

    /**
     * @var string[]
     */
    protected $usedCurrencies;

    public function __construct(
        UsedCurrenciesProvider $usedCurrenciesProvider
    ) {
        $this->usedCurrencies = $usedCurrenciesProvider->getCurrencies();
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function setMinDate(DateTime $date): void
    {
        $this->minDate = $date;
    }
    public function addDataModifier(ExchangeRatesRequestDataModifierInterface $modifier): void
    {
        $this->dataModifiers[] = $modifier;
    }

    /**
     * @param ExchangeRateInterface[] $rates
     * @return ExchangeRateInterface[]
     */
    protected function modifyRates(array $rates): array
    {
        if (empty($this->dataModifiers)) {
            return $rates;
        }

        foreach ($this->dataModifiers as $modifier) {
            $modifier->modifyMany($rates);
        }

        return $rates;
    }

    protected function validateDate(): bool
    {
        return !empty($this->minDate) && $this->date >= $this->minDate
            || empty($this->minDate);
    }
}