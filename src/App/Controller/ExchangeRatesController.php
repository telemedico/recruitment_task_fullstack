<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    private ExchangeRatesService $ExchangeRatesService;

    public function __construct(ExchangeRatesService $ExchangeRatesService)
    {
        $this->ExchangeRatesService = $ExchangeRatesService;
    }

    public function getExchangeRates(Request $request): Response
    {
        $date = $request->query->get('date');

        try {
            $rates = $this->ExchangeRatesService->getExchangeRates($date);
            return new Response(
                json_encode($rates),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (\Exception $e) {
            $rates = $this->ExchangeRatesService->getExchangeRates($date);
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                Response::HTTP_BAD_REQUEST,
                ['Content-type' => 'application/json']
            );
        }
    }
}
