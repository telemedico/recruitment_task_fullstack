<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ExchangeRate\ExchangeRatesAPIService;
use App\Service\ExchangeRate\ExchangeRatesService;

class ExchangeRatesController extends AbstractController {
    private $exchangeRatesApiService;
    private $exchangeRatesService;

    public function __construct(ExchangeRatesAPIService $exchangeRatesApiService,  ExchangeRatesService $exchangeRatesService) {
        $this->exchangeRatesApiService = $exchangeRatesApiService;
        $this->exchangeRatesService = $exchangeRatesService;
    }

    public function getExchangeRates(Request $request): JsonResponse {
        $selectedDate = $request->query->get('date') ?? '';
        if (date('Y-m-d') === $selectedDate) {
            $selectedDate = '';
        }
        $selectedDateIntance = \DateTime::createFromFormat('Y-m-d', $selectedDate);
        if ($selectedDate !== '' && (!$selectedDateIntance || ($selectedDateIntance && $selectedDateIntance->format('Y-m-d') !== $selectedDate))) {
            return new JsonResponse(['error' => 'Invalid date format. Expected format: YYYY-mm-dd'], 400);
        }

        $now = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));
        $noon = new \DateTime('today 12:00:00', new \DateTimeZone('Europe/Warsaw'));

        $latestDate = ($now > $noon) ? date('Y-m-d') : date('Y-m-d', strtotime('-1 day'));
        $ratesApiLatest = $this->exchangeRatesApiService->getExchangeRates($latestDate);
        $ratesLatest = $this->exchangeRatesService->processExchangeRates($ratesApiLatest['rates']);

        if ($selectedDate !== '') {
            $ratesApiSelected = $this->exchangeRatesApiService->getExchangeRates($selectedDate);
            $ratesSelected = $this->exchangeRatesService->processExchangeRates($ratesApiSelected['rates']);
            foreach ($ratesLatest as $ind => $r) {
                $ratesLatest[$ind]['selected'] = $this->getRateByCode($ratesSelected, $r['code']) ?? null;
            }
        }

        $rslt = [
            'rates' => $ratesLatest,
            'latestDate' => $ratesApiLatest['effectiveDate'],
            'isUpdatedToday' => ($now > $noon),
            'selectedDate' => $selectedDate
        ];
        return new JsonResponse($rslt);
    }


    function getRateByCode($rates, $code) {
        $result = array_filter($rates, function ($rate) use ($code) {
            return $rate['code'] === $code;
        });
        if (empty($result)) {
            return null;
        }
        return array_values($result)[0];
    }
}
