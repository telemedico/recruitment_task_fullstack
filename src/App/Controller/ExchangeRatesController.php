<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesController extends AbstractController
{
  protected $client;

  public function __construct(HttpClientInterface $client)
  {
    $this->client = $client;
  }

  public function index(string $date = "now"): Response
  {
    // definitions
    $supportedCurrencies = ["EUR", "USD", "CZK", "IDR", "BRL"];
    $convertibleCurrencies = ["EUR", "USD"];
    $buyMarginForConvertibleCurrencies = -0.05;
    $sellMarginForConvertibleCurrencies = 0.07;
    $sellMargin = 0.15;

    // fetch rates for current day
    $NBPdata = $this->fetchExchangeRatesTableA();

    $ts = strtotime($date);
    if (!$ts) {
      $ts = strtotime("now");
    }
    $formattedDate = date("Y-m-d", $ts);

    $server_data = [
      "date" => $formattedDate, //past (if specified) or current date
      "supportedCurrencies" => $supportedCurrencies,
      "NBPdata" => $NBPdata,
      "convertibleCurrencies" => $convertibleCurrencies,
      "buyMarginForConvertibleCurrencies" => $buyMarginForConvertibleCurrencies,
      "sellMarginForConvertibleCurrencies" => $sellMarginForConvertibleCurrencies,
      "sellMargin" => $sellMargin,
    ];

    return $this->render("exchange_rates/index.html.twig", [
      "server_data" => json_encode($server_data),
    ]);
  }

  public function api(string $date): Response
  {
    $NBPdata = json_encode($this->fetchExchangeRatesTableA($date));

    return new Response($NBPdata, Response::HTTP_OK, [
      "Content-type" => "application/json",
    ]);
  }

  protected function fetchExchangeRatesTableA(string $date = ""): array
  {
    $response = $this->client->request(
      "GET",
      "https://api.nbp.pl/api/exchangerates/tables/A/$date"
    );
    $statusCode = $response->getStatusCode();
    if ($statusCode == 200) {
      $content = $response->toArray()[0];
    } else {
      $content = [];
    }
    return $content;
  }
}
