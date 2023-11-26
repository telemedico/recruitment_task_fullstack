<?php

declare(strict_types=1);

namespace App\Controller;

use App\API\NBP\ApiNBP;
use App\Helpers\DateValidation;
use App\Helpers\ResponseFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    const SUPPORTED_CURRENCIES = ['EUR','USD','CZK','IDR','BRL'];
    const PURCHASE_CURRENCIES = ['EUR','USD'];

    /**
     * Get currencies rates.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getExchangeRates(Request $request): JsonResponse
    {
        $date = $request->query->get('date') ?? date($_ENV['DATE_FORMAT']);

        $validationErrorDate = DateValidation::validateDate($date);
        if (!empty($validationErrorDate)) {
            return ResponseFormat::responseError(Response::HTTP_BAD_REQUEST, 'Incorrect date', $validationErrorDate);
        }

        $rates = [];
        $supportedCurrencies = self::SUPPORTED_CURRENCIES;
        $purchaseCurrencies = self::PURCHASE_CURRENCIES;
        $apiNBP = new ApiNBP;

        foreach ($supportedCurrencies as $supportedCurrency) {
            $apiResponse = $apiNBP->getRate($supportedCurrency, $date);

            if ($apiResponse->getStatusCode() == Response::HTTP_OK) {
                $nbpRate = json_decode($apiResponse->getContent(), true);
                $rates[] = self::prepareExchangeRatesItem($nbpRate, $purchaseCurrencies);
            } else {
                return $apiResponse;
            }
        }

        return new JsonResponse($rates);
    }

    /**
     * Prepares element of an array ExchangeRates.
     *
     * @param array $item
     * @param array $purchaseCurrencies
     * @return array
     */
    public static function prepareExchangeRatesItem(array $item, array $purchaseCurrencies): array
    {
        $purchaseRate = null;
        $sellRate = $item['midRate'] + $_ENV['UNSUPPORTED_SALES_MARGIN'];

        if (in_array($item['code'], $purchaseCurrencies)) {
            $purchaseRate = $item['midRate'] + $_ENV['PURCHASE_MARGIN'];
            $sellRate = $item['midRate'] + $_ENV['SALES_MARGIN'];
        }

        return [
            'code'=> $item['code'],
            'name'=> $item['name'],
            'midRate'=> $item['midRate'],
            'purchase' => $purchaseRate,
            'sell' => $sellRate,
        ];
    }
}
