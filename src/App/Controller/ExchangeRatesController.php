<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\NationalBankService;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExchangeRatesController extends AbstractController
{
    private $bankService;
    private $currencies;

    public function __construct(NationalBankService $bankService, array $currencies)
    {
        $this->bankService = $bankService;
        $this->currencies = $currencies;
    }

    /**
     * @return JsonResponse
     */
    public function getList(?string $date = null): JsonResponse
    {
        if ($date === null) {
            $date = (new \DateTime())->format('Y-m-d');
        }
        try {
            $rates = $this->bankService->getTables($date);
        } catch (InvalidArgumentException | RuntimeException $exception) {
            return new JsonResponse(['error' => 'Something went wrong, try again later'], 500);
        }
        return new JsonResponse($rates->toArray(), 200);
    }
}
