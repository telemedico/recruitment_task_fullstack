<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


define('NBP_API_BASE_URT', 'http://api.nbp.pl/api/exchangerates/tables/A/');
define('NBP_LATEST_API_URT', 'http://api.nbp.pl/api/exchangerates/tables/A/?format=json');
define('MIN_DATE', '2023-01-01');

class ExchangeRatesController extends AbstractController
{
    
    public function exchangeNbpTable(Request $request): Response
    {

        $date = $request->query->get('date');

        if (is_string($date) && !$this->validDate($date)) {
            return new Response(
                'Wrong date',
                Response::HTTP_BAD_REQUEST,
                ['Content-type' => 'text/plain']
            );
        }

        $url = $this->prepareNbpApiUrl($date);
        $response = HttpClient::create()->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return new Response(
                'Wrong response from NBP Api, URL: '.$url,
                $response->getStatusCode(),
                ['Content-type' => 'text/plain']
            );
        }

 
        return new Response(
            $response->getContent(),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );

    }

    private function prepareNbpApiUrl($date): String {
        return is_string($date) 
            ? NBP_API_BASE_URT.$date.'/?format=json'
            : NBP_LATEST_API_URT;
    }


    private function validDate($requestDate): bool {
        $pattern = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
        return strlen($requestDate) === 10 && preg_match($pattern, $requestDate) && $requestDate >= MIN_DATE;
    }

}
