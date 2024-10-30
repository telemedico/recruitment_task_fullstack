<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Trade;

use App\Exception\NoCalculationStrategyException;
use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\ExchangeRatesRequestDataModifierInterface;

class TradeRateModifier implements ExchangeRatesRequestDataModifierInterface
{
    /**
     * @var CurrencyTradeRateCalculationInterface
     */
    private $buyingRateCalc;

    /**
     * @var CurrencyTradeRateCalculationInterface
     */
    private $sellingRateCalc;

    /**
     * @var CurrencyTradeRateCalculationInterface[]
     */
    private $buyingRateCalcList;

    /**
     * @var CurrencyTradeRateCalculationInterface[]
     */
    private $sellRateCalcList;

    public function __construct(
        array $buyingRateCalcList,
        array $sellRateCalcList
    ) {
        $this->buyingRateCalcList = $buyingRateCalcList;
        $this->sellRateCalcList = $sellRateCalcList;
    }

    public function setBuyingCalcStrategy(CurrencyTradeRateCalculationInterface $buyingRateCalc): void
    {
        $this->buyingRateCalc = $buyingRateCalc;
    }

    public function setSellingCalcStrategy(CurrencyTradeRateCalculationInterface $sellingRateCalc): void
    {
        $this->sellingRateCalc = $sellingRateCalc;
    }

    /**
     * @throws NoCalculationStrategyException
     */
    public function modify(ExchangeRateInterface $rate): ExchangeRateInterface
    {
        $buyingStrategy = $this->buyingRateCalcList[$rate->getCurrency()] ?? false;
        $sellingStrategy = $this->sellRateCalcList[$rate->getCurrency()] ?? false;

        if (!$buyingStrategy || !$sellingStrategy) {
            throw new NoCalculationStrategyException("Calculation strategy for {$rate->getCurrency()} doesn't exist.");
        }

        $this->setBuyingCalcStrategy($buyingStrategy);
        $this->setSellingCalcStrategy($sellingStrategy);

        $rate->setBuyingRate($this->buyingRateCalc->calculate($rate));
        $rate->setSellingRate($this->sellingRateCalc->calculate($rate));

        return $rate;
    }

    /**
     * @throws NoCalculationStrategyException
     */
    function modifyMany(array $rates): array
    {
        foreach ($rates as $rate) {
            $this->modify($rate);
        }

        return $rates;
    }
}