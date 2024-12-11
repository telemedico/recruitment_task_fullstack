<?php

declare(strict_types=1);

namespace App\UI\Api\Presenter;

use App\Application\Dto\CurrenciesCollectionDto;
use App\Application\Dto\CurrencyDto;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetExchangeRatesListResponsePresenter
{
    public static function respond(CurrenciesCollectionDto $currencies): JsonResponse
    {
        return new JsonResponse(
            array_map(
                static function (CurrencyDto $currency): array {
                    return [
                        'name' => $currency->getName(),
                        'code' => $currency->getCode(),
                        'exchangeRate' => [
                            'mid' => $currency->getExchangeRate()->getMid(),
                            'buyRate' => $currency->getExchangeRate()->getBuyRate(),
                            'sellRate' => $currency->getExchangeRate()->getSellRate(),
                        ],
                    ];
                },
                $currencies->getCurrencies()
            )
        );
    }
}
