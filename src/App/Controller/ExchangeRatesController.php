<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class ExchangeRatesController extends AbstractController
{

    public function viewExchangeRates(Request $request): JsonResponse
    {
        $date = $request->query->get('date', date('Y-m-d'));

        $validCodes = [
            'USD',
            'EUR',
            'CZK',
            'IDR',
            'BRL'
        ];

        $exchangeRatesArray = [];
        $httpClient = HttpClient::create();

        foreach ($validCodes as $validCode) {
            $url = 'https://api.nbp.pl/api/exchangerates/rates/A/'.$validCode.'/'.$date.'/?format=json';
            $response = $httpClient->request('GET', $url);

            $content = $response->getContent();
            $decoded = json_decode($content);

            $rates = $decoded->rates;
            $rate = $rates[0];

            $exchangeRatesArray[] = [
                'code' => $decoded->code,
                'mid' => $rate->mid,
            ];
        }

        return $this->json($exchangeRatesArray);
    }
}
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Contracts\HttpClient\HttpClientInterface;
// use Psr\Log\LoggerInterface;

// class ExchangeRatesController extends AbstractController
// {
//     public function __construct(HttpClientInterface $client, LoggerInterface $logger)
//     {
//         $this->client = $client;
//         $this->logger = $logger;
//     }

//     public function index(): JsonResponse
//     {
//         $response = $this->client->request(
//             'GET',
//             'https://api.nbp.pl/api/exchangerates/tables/A/?format=json'
//         );

//         $content = $response->toArray();
//         $this->logger->info('API Response', ['response' => $content]);
//         return $this->json($content);
//     }
// }

