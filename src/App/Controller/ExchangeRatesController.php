<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getExchangeRates(Request $request): JsonResponse
    {
        $date = $request->query->get('date', (new \DateTime())->format('Y-m-d'));
        $currencies = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

        $exchangeRates = [];

        foreach ($currencies as $currency) {
            $response = $this->client->request('GET', "https://api.nbp.pl/api/exchangerates/rates/A/$currency/$date?format=json");

            if ($response->getStatusCode() !== 200) {
                return new JsonResponse(['error' => 'Unable to fetch data from NBP'], $response->getStatusCode());
            }

            $data = $response->toArray();
            $averageRate = $data['rates'][0]['mid'];

            $buyRate = null;
            $sellRate = null;

            if (in_array($currency, ['EUR', 'USD'])) {
                $buyRate = $averageRate - 0.05;
                $sellRate = $averageRate + 0.07;
            } else {
                $sellRate = $averageRate + 0.15;
            }

            $exchangeRates[] = [
                'currency' => $currency,
                'averageRate' => $averageRate,
                'buyRate' => $buyRate,
                'sellRate' => $sellRate,
            ];
        }

        return new JsonResponse($exchangeRates);
    }

}
