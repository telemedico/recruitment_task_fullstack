<?php

declare(strict_types=1);

namespace App\Controller;

use App\Query\CurrencyPricesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    /**
     * @var CurrencyPricesQuery
     */
    private $currencyPricesQuery;

    public function __construct(CurrencyPricesQuery $currencyPricesQuery)
    {
        $this->currencyPricesQuery = $currencyPricesQuery;
    }

    public function fetchAllForDate(string $date): Response
    {
        $currencyPrices = $this->currencyPricesQuery
            ->fetchAllForDateTime(\DateTimeImmutable::createFromFormat('Y-m-d', $date));

        return new Response(
            json_encode($currencyPrices),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
