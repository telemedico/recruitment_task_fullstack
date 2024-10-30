<?php

declare(strict_types=1);

namespace App\Controller;

use App\ExchangeRates\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExchangeRatesController extends AbstractController
{

    /**
     * @var ExchangeRatesService $exchangeRatesService
     */
    private $exchangeRatesService;

    public function __construct(ExchangeRatesService $exchangeRateService)
    {
        $this->exchangeRatesService = $exchangeRateService;
    }

    public function officeRates(?string $date): JsonResponse
    {
        $officeRates = $this->exchangeRatesService->getOfficeRates($date);
        return $this->json($officeRates);
    }
}
