<?php

declare(strict_types=1);

namespace App\UI\Api\Action;

use App\Application\ExchangeRatesServiceInterface;
use App\Application\Query\GetExchangeRatesListQuery;
use App\UI\Api\Exception\ValidateRequestException;
use App\UI\Api\Presenter\GetExchangeRatesListResponsePresenter;
use App\UI\Api\Validator\GetExchangeRatesListValidator;
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

    /**
     * @throws ValidateRequestException
     */
    public function __invoke(Request $request): Response
    {
        $date = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $request->get('requestDate', (new DateTimeImmutable())->format('Y-m-d'))
        );

        GetExchangeRatesListValidator::validate($date);

        $currencies = $this->exchangeRatesService->getList(
            new GetExchangeRatesListQuery($date)
        );

        return GetExchangeRatesListResponsePresenter::respond($currencies);
    }
}
