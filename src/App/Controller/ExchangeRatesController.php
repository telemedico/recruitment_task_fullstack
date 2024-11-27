<?php

declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/exchange-rates")
 */
class ExchangeRatesController extends AbstractController
{
    /**
     * @Route("/{date}")
     */
    public function getExchangeRatesByDate(DateTime $date): JsonResponse {
        return $this->json($date);
    }
}
