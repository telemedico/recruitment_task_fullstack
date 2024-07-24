<?php

declare(strict_types=1);

namespace App\Exchange\Presentation\Controller;


use App\Exchange\Domain\Service\CurrencyServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ExchangeRatesController extends AbstractController
{
    private CurrencyServiceInterface $currencyService;
    private SerializerInterface $serializer;


    public function __construct(CurrencyServiceInterface $currencyService,
                                SerializerInterface      $serializer
    )
    {
        $this->currencyService = $currencyService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/exchange-rates/{date}", name="exchange_rates")
     */
    public function getExchangeRates(string $date): JsonResponse
    {
        $exchangeRates = $this->currencyService->getExchangeRates($date);


        $serializedExchangeRates = $this->serializer->serialize($exchangeRates, 'json', ['groups' => 'read']);

        return new JsonResponse($serializedExchangeRates, Response::HTTP_OK, [], true);
    }
}
