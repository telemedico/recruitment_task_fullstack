<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExchangeRatesController extends AbstractController
{
    private $exchangeRatesService;

    public function __construct(ExchangeRatesService $exchangeRatesService)
    {
        $this->exchangeRatesService = $exchangeRatesService;
    }

    /**
     * Retrieves exchange rates for the specified date and currencies.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRates(Request $request): JsonResponse
    {
        $date = $request->query->get('date');
        $currencies = $request->query->get('currencies', []);

        if (!is_array($currencies)) {
            return new JsonResponse([
                'message' => 'Currencies parameter must be an array.',
                'code' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        // 5.
        try {
            $data = $this->exchangeRatesService->getExchangeRates($date, $currencies);
            return new JsonResponse($data, Response::HTTP_OK);
        } catch (HttpException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => 'An unexpected error occurred.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
