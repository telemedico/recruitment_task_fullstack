<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CurrencyPriceViewServiceInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/exchange-rates")
 */
class ExchangeRatesController extends AbstractController
{
    /** @var CurrencyPriceViewServiceInterface $currencyPriceViewService */
    private $currencyPriceViewService;
    public function __construct(
        CurrencyPriceViewServiceInterface $currencyPriceViewService
    ) {
        $this->currencyPriceViewService = $currencyPriceViewService;
    }

    /**
     * @Route("/{date}")
     */
    public function getExchangeRatesByDate(DateTime $date): JsonResponse {
        $result = $this->currencyPriceViewService->getAllCurrencyPricesByDate($date);
        return $this->json($result);
    }
}
