<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CurrencyService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExchangeRatesController extends AbstractController
{
    public function getCurrencies(Request $request, CurrencyService $currencyService): JsonResponse
    {
        $date = $request->get('date') ? new DateTime($request->get('date')) : null;

        try {
            $responseContent = $currencyService->getCurrenciesForDate($date);

            return $this->json($responseContent, Response::HTTP_OK, ['Content-type' => 'application/json']);
        } catch (Throwable $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode(), ['Content-type' => 'application/json']);
        }
    }
}
