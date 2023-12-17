<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ApiService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    private $apiService;
    private $exchanges = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
    private $currWithMargin = ['EUR', 'USD'];
    private $buyRateMargin = -0.5;
    private $sellRateMargin = 0.07;
    private $sellRateMarginRestCurr = 0.15;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     */
    public function getExchangeRates(string $date): JsonResponse
    {
        $todayFormatted = (new DateTime())->format('Y-m-d');
        $finalExchangeRates = $this->processRates($todayFormatted);

        if ($date !== $todayFormatted) {
            $finalExchangeRates += $this->processRates($date);
        }

        return $this->json($finalExchangeRates);
    }

    /**
     * @throws Exception
     */
    private function processRates(string $date): array
    {
        $exchangeRates = [];

        $rates = $this->apiService->connectToNBP($date);

        if ($rates) {
            foreach ($rates as $exchange) {
                foreach ($exchange['rates'] as $rate) {
                    $currencyCode = (string)$rate['code'];
                    $currencyName = (string)$rate['currency'];

                    if (in_array($currencyCode, $this->exchanges)) {
                        $mid = (float)$rate['mid'];
                        [$buyRate, $sellRate] = $this->calculateRates($currencyCode, $mid);

                        $exchangeRates[$date][$currencyCode] = [
                            'sellRate' => $sellRate,
                            'buyRate' => $buyRate,
                            'mid' => $mid,
                            'currency' => $currencyName,
                            'date' => $exchange['effectiveDate'],
                        ];
                    }
                }
            }
        }

        return $exchangeRates;
    }

    private function calculateRates(string $currencyCode, float $mid): array
    {
        $buyRate = $sellRate = false;

        if (in_array($currencyCode, $this->currWithMargin)) {
            $buyRate = $mid + $this->buyRateMargin;
            $sellRate = $mid + $this->sellRateMargin;
        } else {
            $sellRate = $mid + $this->sellRateMarginRestCurr;
        }

        return [$buyRate, $sellRate];
    }

    public function display(Request $request): Response
    {
        return $this->render(
            'exchange_rates/app-root.html.twig'
        );
    }
}
