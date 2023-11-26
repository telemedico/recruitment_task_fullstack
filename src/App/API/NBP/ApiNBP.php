<?php

namespace App\API\NBP;

use App\Helpers\ResponseFormat;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiNBP
{
    /**
     * @var HttpClient The http client API.
     */
    private $apiClient;

    /**
     * @var string The http client API.
     */
    private $baseUrl;

    /**
     * Constructor for ApiNBP
     */
    public function __construct()
    {
        $this->apiClient = HttpClient::create();
        $this->baseUrl =  $_ENV['BASE_URL_API_NBP'];
    }

    /**
     * Get currency rate from NBP Api.
     *
     * @param string $currency
     * @param string $date
     * @return JsonResponse
     */
    public function getRate($currency, $date): JsonResponse
    {
        try {
            $data = $this->apiClient->request('GET', $this->baseUrl . "$currency/$date/?format=json")->toArray();
            
            return new JsonResponse([
                'code' => $data['code'],
                'name' => $data['currency'],
                'midRate' => $data['rates'][0]['mid'],
            ]);

        } catch (Exception $e) {
            $message = 'Error while downloading the currency rate ';
            return ResponseFormat::responseError(Response::HTTP_BAD_REQUEST, $message);
        }
    }

}