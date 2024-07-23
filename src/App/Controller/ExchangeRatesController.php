<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExchangeRatesController extends AbstractController
{
    public function index(ExchangeRatesService $exchangeRatesService, ?string $date): JsonResponse
    {
        try {
            return new JsonResponse($exchangeRatesService->provideSelectedRates($date));
        } catch (\Throwable $e) {
            return new JsonResponse(['messge' => 'Some error occured']);
        }
    }
}
