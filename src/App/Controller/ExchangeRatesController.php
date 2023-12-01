<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeCurrencyDataModelConverter;
use App\Service\NbpApiException;
use App\Service\NbpApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


define('NBP_API_BASE_URT', 'http://api.nbp.pl/api/exchangerates/tables/A/');
define('NBP_LATEST_API_URT', 'http://api.nbp.pl/api/exchangerates/tables/A/?format=json');
define('MIN_DATE', '2023-01-01');

class ExchangeRatesController extends AbstractController
{
    public function exchangeNbpTable(Request $request,
        NbpApiService $nbpApiService, 
        ExchangeCurrencyDataModelConverter $exchangeCurrencyDataModelConverter
    ): Response
    {

        $date = $request->query->get('date');

        if (is_string($date) && !$this->validDate($date)) {
            return new Response(
                'Wrong date',
                Response::HTTP_BAD_REQUEST,
                ['Content-type' => 'text/plain']
            );
        }

        $onlyLatestData = !is_string($date) || $date === date('Y-m-d');

        $responseData = null;

        try {
            $responseData = $nbpApiService->fetchNbpApi($date, $onlyLatestData);
        } catch (NbpApiException $e) {
            $nbpResponse = $e->getResponse();
            return new Response(
                'Wrong NBP APi response',
                $nbpResponse->getStatusCode(),
                ['Content-type' => 'text/plain']
            );
        }
        return new Response(
            json_encode($exchangeCurrencyDataModelConverter->calculateExchangeDataModel($responseData, $onlyLatestData)),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );

    }

    private function validDate($requestDate): bool {
        $pattern = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
        return strlen($requestDate) === 10 && preg_match($pattern, $requestDate) && $requestDate >= MIN_DATE;
    }

}
