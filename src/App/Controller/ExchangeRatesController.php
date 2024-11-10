<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    /**
     * @var ExchangeRatesService
     */
    private $exchangeRatesService;

    public function __construct(ExchangeRatesService $exchangeRatesService)
    {
        $this->exchangeRatesService = $exchangeRatesService;
    }

    public function index(Request $request): Response
    {
        $date = $request->get('date');
        $dateTime = new \DateTimeImmutable($date);

        $rates = $this->exchangeRatesService->getAllCurrencyRates($dateTime);

        $responseContent = json_encode([
            'rates' => $rates
        ]);

        return new Response(
            $responseContent,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
