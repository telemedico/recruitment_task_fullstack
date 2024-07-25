<?php

declare(strict_types=1);

namespace App\Exchange\Presentation\Controller;

use App\Exchange\Application\Exception\NoExchangeRatesFoundException;
use App\Exchange\Domain\Service\CurrencyServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ExchangeRatesController extends AbstractController
{
    /**
     * @var CurrencyServiceInterface
     */
    private $currencyService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(CurrencyServiceInterface $currencyService, SerializerInterface $serializer)
    {
        $this->currencyService = $currencyService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/exchange-rates/{date}", name="exchange_rates")
     */
    public function getExchangeRates(string $date): JsonResponse
    {
        try {
            $dateTime = new \DateTimeImmutable($date);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => 'Invalid date format.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $exchangeRates = $this->currencyService->getExchangeRates($dateTime);
            $serializedExchangeRates = $this->serializer->serialize($exchangeRates, 'json', ['groups' => ['read']]);

            return new JsonResponse($serializedExchangeRates, Response::HTTP_OK, [], true);
        } catch (NoExchangeRatesFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
