<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CurrencyService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    public function getCurrencies(Request $request, CurrencyService $currencyService): JsonResponse
    {
        $date            = $request->get('date') ? new DateTime($request->get('date')) : null;
        $responseContent = $currencyService->getCurrenciesForDate($date);

        return $this->json($responseContent, Response::HTTP_OK, ['Content-type' => 'application/json']);
    }
}
