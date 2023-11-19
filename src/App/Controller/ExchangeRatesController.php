<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    private ApiService $apiService;
    private array $exchanges = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
    private array $currencyWithMargin = ['EUR', 'USD'];
    private float $sellMargin = -0.5;
    private float $buyMargin = 0.07;
    private float $sellMarginRestCurrency = 0;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     */
    public function getExchangeRates(): JsonResponse
    {
        $finalExchangeRates = [];
        $nbpData = $this->apiService->connectToNBP();

        foreach ($nbpData as $exchange) {
            $date = $exchange['effectiveDate'];

            foreach ($exchange['rates'] as $rate) {
                $currencyCode = (string)$rate['code'];
                if (in_array($currencyCode, $this->exchanges)) {
                    $finalExchangeRates[] = $rate;
                    $currencyName = (string)$rate['currency'];
                    $mid = (float)$rate['mid'];
                }
            }
        }

        return $this->json($finalExchangeRates);
    }

    public function display(Request $request): Response
    {
        return $this->render(
            'exchange_rates/app-root.html.twig'
        );
    }
}
