<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExchangeRatesController extends AbstractController {
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService) {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function getTodayExchangeRates(): JsonResponse {
        $today = date('Y-m-d');
        $rates = $this->exchangeRateService->getExchangeRatesByDate($today);
        return $this->json($rates);
    }

    public function getExchangeRatesByDate(string $date): JsonResponse {
        $rates = $this->exchangeRateService->getExchangeRatesByDate($date);
        return $this->json($rates);
    }
}