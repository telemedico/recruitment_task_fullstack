<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class NbpApiService {
    
    public static function createClient() {
        return HttpClient::create();
    }

    public function fetchNbpApi(string|null $date, bool $onlyLatestData) {
        $result = [
            'onlyLatestData' => $onlyLatestData
        ];

        $httpClient = static::createClient();
        $response = [];
        if (!$onlyLatestData) {
            $response[historical] = $httpClient->request('GET', $this->prepareNbpApiUrl($date), [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
        }

        $response[latest] = $httpClient->request('GET', $this->prepareNbpApiUrl(), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        
        if ($response[latest]->getStatusCode() !== Response::HTTP_OK) {
            throw new NbpApiException($response[latest]);
        }

        if (!$onlyLatestData && $response[historical]->getStatusCode() !== Response::HTTP_OK) {
            throw new NbpApiException($response[historical]);
        }

        $result[latest] = json_decode($response[latest]->getContent(), true)[0];

        if(!$onlyLatestData) {
            $result[historical] = json_decode($response[historical]->getContent(), true)[0];
        }
        
        return $result;

    }


    private function prepareNbpApiUrl(string|null $date = null): String {
        return is_string($date) 
            ? NBP_API_BASE_URT.$date.'/?format=json'
            : NBP_LATEST_API_URT;
    }


}