<?php

declare(strict_types=1);

namespace App\Controller;

use App\Serializers\CurrencyCollectionSerializer;
use App\Services\CurrencyDataProvider\CurrencyDataProviderException;
use App\Services\CurrencyDataProvider\CurrencyDataProviderInterface;
use App\Services\ExchangeRates\ExchangeRatesProviderException;
use App\Services\ExchangeRates\NoDataException;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    private $currencyDataProvider;
    private $currencyCollectionSerializer;

    public function __construct(
        CurrencyDataProviderInterface $currencyDataProvider,
        CurrencyCollectionSerializer $currencyCollectionSerializer
    ) {
        $this->currencyDataProvider = $currencyDataProvider;
        $this->currencyCollectionSerializer = $currencyCollectionSerializer;
    }

    public function exchangeRates(string $date): JsonResponse
    {
        try {
            $date = new DateTime($date);
        } catch (Exception $e) {
            return new JsonResponse(['error' => 'Nieprawidłowy format daty.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = $this->currencyDataProvider->getData($date);
        } catch (NoDataException $e) {
            return new JsonResponse(['error' => 'Brak danych dla wybranej daty.'], Response::HTTP_BAD_REQUEST);
        } catch (ExchangeRatesProviderException|CurrencyDataProviderException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($this->currencyCollectionSerializer->toArray($data));
    }
}
