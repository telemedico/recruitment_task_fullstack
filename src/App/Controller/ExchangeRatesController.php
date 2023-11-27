<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{

    public function getExchangeRates(Request $request, ExchangeRatesService $exchangeRatesService): Response
    {
        $responseContent = json_encode(
            $exchangeRatesService->getExchangeRates($request->query->get('exchange-rates-date'))
        );
        return new Response(
            $responseContent,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
