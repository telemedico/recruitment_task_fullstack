<?php

namespace App\Service;

use App\Exception\DateNotValidException;
use App\Model\Currency;
use App\Model\CurrencyValue;
use App\Repository\CurrencyRepositoryInterface;
use App\Repository\CurrencyValueRepositoryInterface;
use App\ViewModel\CurrencyPriceViewModel;
use DateTime;

class CurrencyPriceViewViewService implements CurrencyPriceViewServiceInterface
{
    /** @var CurrencyValueRepositoryInterface $currencyValueRepository */
    private $currencyValueRepository;
    /** @var CurrencyRepositoryInterface $currencyRepository */
    private $currencyRepository;
    /** @var CurrencyPriceCalculatorInterface */
    private $priceCalculator;
    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        CurrencyValueRepositoryInterface $currencyValueRepository,
        CurrencyPriceCalculatorInterface $currencyPriceCalculator
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->currencyValueRepository = $currencyValueRepository;
        $this->priceCalculator = $currencyPriceCalculator;
    }

    /** @throws DateNotValidException
     * @return CurrencyPriceViewModel[]
     */
    public function getAllCurrencyPricesByDate(DateTime $date): array
    {
        $this->validateDate($date);
        $allCurrencies = $this->currencyRepository->findAll();
        $currencyPrices = [];
        foreach ($allCurrencies as $currency) {
            $currencyValue = $this->currencyValueRepository->findByCurrencyCodeAndDate($currency->getCode(), $date);
            $buyPrice = $this->getBuyPriceOrNull($currency, $currencyValue);
            $sellPrice = $this->getSellPriceOrNull($currency, $currencyValue);

            $currencyPrices[] = new CurrencyPriceViewModel(
                $currency->getCode(),
                $currencyValue->getName(),
                $buyPrice,
                $sellPrice,
                $currencyValue->getPrice()
            );
        }
        return $currencyPrices;
    }

    /** @throws DateNotValidException */
    private function validateDate(DateTime $dateTime): void
    {
        $start = new DateTime('2023-01-01 00:00:00');
        if ($dateTime < $start) {
            throw new DateNotValidException(
                sprintf(
                    'Provided date should be greater than %s',
                    $start->format("Y-m-d")
                )
            );
        }
    }

    private function getBuyPriceOrNull(Currency $currency, CurrencyValue $currencyValue): ?float
    {
        return is_null($currency->getCommissionRemove()) ?
            null :
            $this->priceCalculator->calculateBuyPrice(
                $currencyValue->getPrice(),
                $currency->getCommissionRemove()
            );
    }

    private function getSellPriceOrNull(Currency $currency, CurrencyValue $currencyValue): ?float
    {
        return is_null($currency->getCommissionAdd()) ?
            null :
            $this->priceCalculator->calculateSellPrice(
                $currencyValue->getPrice(),
                $currency->getCommissionAdd()
            );
    }
}