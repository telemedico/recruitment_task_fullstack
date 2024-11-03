<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTime;
use DateTimeZone;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExchangeRatesService
{
    private const SUPPORTED_CURRENCIES = [
        'EUR' => 'euro',
        'USD' => 'dolar amerykański',
        'CZK' => 'korona czeska',
        'IDR' => 'rupia indonezyjska',
        'BRL' => 'real brazylijski'
    ];

    private const CURRENCIES_AVAIBLE_TO_BUY = [
        "EUR",
        "USD"
    ];

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getExchangeRates(?string $date = null): array
    {
        $messageGPWNotWorking = '';

        $dateChecked = $this->checkIsDateAvaible($date);

        if ($dateChecked !== (new DateTime($date))->format('Y-m-d')) {
            $messageGPWNotWorking = 'Giełda papierów wartościowych w wybranej dacie nie pracuje';
        }

        $currenciesFromApi = $this->getExchangeRateCurrenciesFromApi($dateChecked);
        $filteredRates = $this->getUsedCurrencies($currenciesFromApi);
        $mergedCurrencies = $this->getMergedTodaySelectedDateCurrencies($filteredRates);
        $formattedCurrencies = $this->formatCurrencies($mergedCurrencies);

        return [
            "messageGPWNotWorking" => $messageGPWNotWorking ?? null,
            "currencies" => $formattedCurrencies,
            "isAvaibleTodayNPB" => $this->checkIsDateAvaible((new DateTime("now", new DateTimeZone('Europe/Warsaw')))->format('Y-m-d')) ? true : false,
            "dateOfRates" => $currenciesFromApi['todayCurrencyRates']['effectiveDate'],
        ];
    }

    private function checkIsDateAvaible(?string $date = null): ?string
    {
        $GPWholidays = [ // obsłużyłem tylko dla 24 trzeba by to rozbudować 
            '2024-01-01', // Poniedziałek, 1 stycznia
            '2024-03-29', // Piątek, 29 marca
            '2024-04-01', // Poniedziałek, 1 kwietnia
            '2024-05-01', // Środa, 1 maja
            '2024-05-03', // Piątek, 3 maja
            '2024-05-30', // Czwartek, 30 maja
            '2024-08-15', // Czwartek, 15 sierpnia
            '2024-11-01', // Piątek, 1 listopada
            '2024-11-11', // Poniedziałek, 11 listopada
            '2024-12-24', // Wtorek, 24 grudnia
            '2024-12-25', // Środa, 25 grudnia
            '2024-12-26', // Czwartek, 26 grudnia
            '2024-12-31', // Wtorek, 31 grudnia
        ];

        if (!$date) {
            return null;
        }

        $dateFormat = DateTime::createFromFormat('Y-m-d', $date);
        if (!($dateFormat && $dateFormat->format('Y-m-d') === $date)) {
            throw new BadRequestHttpException("Data jest nieprawidłowa");
        }
        $dateObj = new DateTime($date, new DateTimeZone("Europe/Warsaw"));

        if ($dateObj->format('Y-m-d') < (new DateTime('2023-01-01'))->format('Y-m-d')) {
            throw new BadRequestHttpException("Data musi być od początku 2023 roku.");
        }

        if ($dateObj->format('Y-m-d') > (new DateTime())->format('Y-m-d')) {
            throw new BadRequestHttpException("Data nie może być starsza od dzisiaj");
        }

        //Sprawdz czy GPW działa
        if (in_array($dateObj->format("l"), ["Saturday", "Sunday"]) || in_array($dateObj->format('Y-m-d'), $GPWholidays)) {
            return null;
        }


        return $dateObj->format('Y-m-d');
    }

    private function getExchangeRateCurrenciesFromApi(?string $date = null): array
    {
        $todayCurrencyRates = [];
        $selectedDayCurrencyRates = [];
        $url = "https://api.nbp.pl/api/exchangerates/tables/A?format=json";

        try {
            $response = $this->client->request('GET', $url);
            $todayCurrencyRates = $response->toArray()[0];
        } catch (\Exception $e) {
            throw new \Exception("Błedy w obsłudze API NBP");
        }

        if ($date && $date !== (new DateTime())->format('Y-m-d')) {
            $dateToURL = $date;
            $url = "https://api.nbp.pl/api/exchangerates/tables/A/{$dateToURL}?format=json";

            try {
                $response = $this->client->request('GET', $url);
                $selectedDayCurrencyRates = $response->toArray()[0];
            } catch (\Exception $e) {
                throw new \Exception("Błedy w obsłudze API NBP");
            }
        }

        return [
            'todayCurrencyRates' => $todayCurrencyRates,
            'selectedDateCurrencyRates' => $selectedDayCurrencyRates
        ];
    }

    private function getMergedTodaySelectedDateCurrencies(array $currenciesFromApi): array
    {
        $todayCurrencyRates = $currenciesFromApi["todayCurrencyRates"];
        $selectedDateCurrencyRates = $currenciesFromApi["selectedDateCurrencyRates"];
        $currencyRates = [];

        foreach ($todayCurrencyRates as $key => $todayRate) {
            $selectedRateMid = isset($selectedDateCurrencyRates[$key]["mid"])
                ? $selectedDateCurrencyRates[$key]["mid"]
                : null;

            $currencyRates[$key] = [
                "currency" => $todayRate["currency"],
                "code" => $todayRate["code"],
                "todayMid" => $todayRate["mid"],
                "selectedDayMid" => $selectedRateMid
            ];
        }

        return $currencyRates;
    }

    private function getUsedCurrencies(array $currenciesFromApi): array
    {
        return array_map(function ($selectedCurrency) {
            if (isset($selectedCurrency['rates'])) {
                return array_filter($selectedCurrency['rates'], function ($rate) {
                    return array_key_exists($rate['code'], self::SUPPORTED_CURRENCIES);
                });
            }

            return $selectedCurrency;
        }, $currenciesFromApi);
    }

    private function formatCurrencies(array $currencies): array
    {
        return array_map(function ($currency) {
            return [
                'code' => $currency['code'],
                'name' => $currency['currency'],
                'currentRates' => $this->getCalculatedRates($currency['code'], $currency['todayMid']),
                'selectedDateRates' => $this->getCalculatedRates($currency['code'], $currency['selectedDayMid']),
            ];
        }, $currencies);
    }

    private function getCalculatedRates(string $code, $midRate): ?array
    {
        if (in_array($code, self::CURRENCIES_AVAIBLE_TO_BUY) && $midRate) {
            return [
                'buyRate' => $midRate - 0.05,
                'sellRate' => $midRate  + 0.07,
                'NBPValue' => $midRate,
            ];
        }

        if ($midRate) {
            return [
                'buyRate' => null,
                'sellRate' => $midRate  + 0.15,
                'NBPValue' => $midRate,
            ];
        }

        return null;
    }
}
