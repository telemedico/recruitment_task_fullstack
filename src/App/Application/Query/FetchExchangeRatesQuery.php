<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Api\NBP\NbpApiInterface;
use App\Application\Config\ConfigProviderInterface;
use App\Domain\Query\FetchExchangeRatesQueryInterface;
use App\Domain\Query\Filter\ExchangeRatesFilter;
use App\Domain\Query\View\ExchangeRatesView;
use App\Domain\Query\View\ExchangeRateView;

final class FetchExchangeRatesQuery implements FetchExchangeRatesQueryInterface
{
    private const MAX_API_CALL_ATTEMPTS = 3;

    /**
     * @var NbpApiInterface
     */
    private $api;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    public function __construct(NbpApiInterface $api, ConfigProviderInterface $configProvider)
    {
        $this->api = $api;
        $this->configProvider = $configProvider;
    }

    public function query(ExchangeRatesFilter $filter): ExchangeRatesView
    {
        $indexedUserRates = $this->getIndexedFilteredRatesArray(
            $this->api->fetchExchangeRatesForDate($filter->getUserDate())
        );

        $latestExchangeRates = $this->getLatestExchangeRates($filter->getLatestDate());

        $latestDate = $latestExchangeRates[0];
        $indexedLatestRates = $this->getIndexedFilteredRatesArray($latestExchangeRates[1]);

        $rates = $this->createExchangeRateViews($indexedUserRates, $indexedLatestRates);

        return new ExchangeRatesView($latestDate, $filter->getUserDate(), ...$rates);
    }

    private function getLatestExchangeRates(\DateTimeImmutable $latestDate): array
    {
        $attempt = 1;

        do {
            $rates = $this->api->fetchExchangeRatesForDate($latestDate);

            if (0 === count($rates)) {
                $latestDate = $latestDate->modify('- 1 day');
                ++$attempt;
            }
        } while (0 === count($rates) && self::MAX_API_CALL_ATTEMPTS >= $attempt);

        return [$latestDate, $rates];
    }

    /**
     * @return array<ExchangeRateView>
     */
    private function createExchangeRateViews(
        array $indexedRatesForUserDate,
        array $indexedRatesForLatestDate
    ): array {
        $ratesForUserDate = [];
        foreach ($indexedRatesForUserDate as $rateForUserDate) {
            $currencyCode = $rateForUserDate['code'];

            $userDateBidRate = isset($rateForUserDate['mid']) && $this->configProvider->isBidAvailableForCurrency($currencyCode)
                ? $rateForUserDate['mid'] - $this->configProvider->getBidShiftForCurrency($currencyCode)
                : null;
            $userDateAskRate = isset($rateForUserDate['mid']) && $this->configProvider->isAskAvailableForCurrency($currencyCode)
                ? $rateForUserDate['mid'] + $this->configProvider->getAskShiftForCurrency($currencyCode)
                : null;

            $ratesForUserDate[$currencyCode] = [
                'currencyCode' => $currencyCode,
                'currencyName' => $rateForUserDate['currency'],
                'userDateBidRate' => $userDateBidRate,
                'userDateAskRate' => $userDateAskRate,
                'userDateNbpRate' => $rateForUserDate['mid'] ?? null,
            ];
        }

        $latestRates = [];
        foreach ($indexedRatesForLatestDate as $latestRate) {
            $currencyCode = $latestRate['code'];

            $latestBidRate = isset($indexedRatesForLatestDate[$currencyCode]['mid']) && $this->configProvider->isBidAvailableForCurrency($currencyCode)
                ? $indexedRatesForLatestDate[$currencyCode]['mid'] - $this->configProvider->getBidShiftForCurrency($currencyCode)
                : null;
            $latestAskRate = isset($indexedRatesForLatestDate[$currencyCode]['mid']) && $this->configProvider->isAskAvailableForCurrency($currencyCode)
                ? $indexedRatesForLatestDate[$currencyCode]['mid'] + $this->configProvider->getAskShiftForCurrency($currencyCode)
                : null;

            $latestRates[$currencyCode] = [
                'currencyCode' => $currencyCode,
                'currencyName' => $latestRate['currency'],
                'latestBidRate' => $latestBidRate,
                'latestAskRate' => $latestAskRate,
                'latestNbpRate' => $indexedRatesForLatestDate[$latestRate['code']]['mid'] ?? null,
            ];
        }

        return array_map(static function (array $rate) {
            return ExchangeRateView::fromArray($rate);
        }, array_values(array_replace_recursive($ratesForUserDate, $latestRates)));
    }

    private function getIndexedFilteredRatesArray(array $rates): array
    {
        $indexedRates = [];

        foreach ($rates[0]['rates'] ?? [] as $rate) {
            if (!$this->isCurrencyAvailable((string) $rate['code'])) {
                continue;
            }

            $indexedRates[$rate['code']] = $rate;
        }

        return $indexedRates;
    }

    private function isCurrencyAvailable(string $currencyCode): bool
    {
        return in_array($currencyCode, $this->configProvider->getAvailableCurrencies(), true);
    }
}
