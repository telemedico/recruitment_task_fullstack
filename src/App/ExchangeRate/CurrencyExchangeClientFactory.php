<?php

declare(strict_types = 1);

namespace App\ExchangeRate;

use DateTime;

class CurrencyExchangeClientFactory
{
    /**
     * @var ExchangeRatesRequestDataModifierInterface[]
     */
    private $rateModifiers;

    /**
     * @var CurrencyExchangeClientInterface
     */
    private $exchangeRateRequest;

    /**
     * @var ?DateTime
     */
    private $date;
    /**
     * @var string|null
     */
    private $minDate;

    public function __construct(
        CurrencyExchangeClientInterface $exchangeRateRequest,
        ?DateTime $date = null,
        ?string $minDate = null,
        array $rateModifiers = []
    ) {
        $this->rateModifiers = $rateModifiers;
        $this->exchangeRateRequest = $exchangeRateRequest;

        $this->date = $date ?: new DateTime();
        $this->minDate = $minDate;
    }

    public function create(): CurrencyExchangeClientInterface
    {
        $this->exchangeRateRequest->setDate($this->date);

        if ($this->minDate !== null) {
            $this->exchangeRateRequest->setMinDate(new DateTime($this->minDate));
        }

        foreach ($this->rateModifiers ?? [] as $modifier) {
            $this->exchangeRateRequest->addDataModifier($modifier);
        }

        return $this->exchangeRateRequest;
    }
}