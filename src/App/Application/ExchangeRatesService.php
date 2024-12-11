<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Dto\CurrenciesCollectionDto;
use App\Application\Dto\CurrencyDto;
use App\Application\Dto\ExchangeRateDto;
use App\Application\Query\GetExchangeRatesListQuery;
use App\Domain\ExchangeRate;
use App\Domain\ExchangeRateRepositoryInterface;
use DateTimeImmutable;

final class ExchangeRatesService implements ExchangeRatesServiceInterface
{
    private $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    public function getList(GetExchangeRatesListQuery $query): CurrenciesCollectionDto
    {
        $exchangeRates = $this->exchangeRateRepository->getList($query->getDate());

        return new CurrenciesCollectionDto(
            ...array_map(
                function (ExchangeRate $exchangeRate): CurrencyDto {
                    return $this->getCurrencyDto($exchangeRate);
                },
                $exchangeRates
            )
        );
    }

    private function getCurrencyDto(ExchangeRate $exchangeRate): CurrencyDto
    {
        return new CurrencyDto(
            $exchangeRate->getCurrency()->getName(),
            $exchangeRate->getCurrency()->getCode(),
            new ExchangeRateDto(
                $exchangeRate->getMidRate(),
                $exchangeRate->getBuyRate(),
                $exchangeRate->getSellRate()
            )
        );
    }
}
