<?php

declare(strict_types=1);

namespace App\UI\Api\Action;

use App\Application\ExchangeRatesServiceInterface;
use App\Application\Query\GetExchangeRatesListQuery;
use App\UI\Api\Presenter\GetExchangeRatesListResponsePresenter;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetExchangeRatesList
{
    private $exchangeRatesService;

    public function __construct(ExchangeRatesServiceInterface $exchangeRatesService)
    {
        $this->exchangeRatesService = $exchangeRatesService;
    }

    public function __invoke(Request $request): Response
    {
        $date = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $request->request->get('requestDate', (new DateTimeImmutable())->format('Y-m-d'))
        );

        $currencies = $this->exchangeRatesService->getList(
            new GetExchangeRatesListQuery($date)
        );

        return GetExchangeRatesListResponsePresenter::respond($currencies);
    }
}
