<?php

declare(strict_types=1);

namespace App\Controller;

use App\CurrencyRateProviders\CurrencyRateProviderInterface;
use App\Dto\CurrencyRateDto;
use App\Dto\CurrencyRatesDto;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class ExchangeRatesController extends AbstractController
{
    public function __invoke(CurrencyRateProviderInterface $currencyRateProvider, ?string $date = null): JsonResponse
    {
        return $this->buildResponse(
            $currencyRateProvider->getCurrencyRates(
                $this->resolveDate($date)
            )
        );
    }

    private function buildResponse(CurrencyRatesDto $currencyRates): JsonResponse
    {
        return new JsonResponse([
            'date' => $currencyRates->getDate()->format('c'),
            'rates' => array_map(function (CurrencyRateDto $rate) {
                return $rate->toArray();
            }, $currencyRates->getRates())
        ]);
    }

    private function resolveDate(?string $date): ?DateTime
    {
        if (null === $date) {
            return null;
        }

        try {
            $rateDate = new DateTime($date);
        } catch (Throwable $throwable) {
            throw new BadRequestHttpException('Invalid date.');
        }

        if ($rateDate < new DateTime('2023-01-01 00:00:00')) {
            throw new BadRequestHttpException('Date is too old.');
        }

        return $rateDate;
    }
}
