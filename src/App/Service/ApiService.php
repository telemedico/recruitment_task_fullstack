<?php

namespace App\Service;

use Exception;


class ApiService
{
    private $nbp_api;

    public function __construct(
        array $nbp_api
    )
    {
        $this->nbp_api = $nbp_api;
    }

    /**
     * @throws Exception
     */
    public function connectToNBP(string $date)
    {
        $apiEndpoint = $this->nbp_api["exchange_rates_all"] . $date . '?format=json';

        try {
            $apiData = file_get_contents($apiEndpoint);
        } catch (Exception $exception) {
            return false;
        }


        if ($apiData === false) {
            throw new Exception('Nie udało się pobrać danych z API.');
        }

        $decodedData = json_decode($apiData, true);

        if ($decodedData === null) {
            throw new Exception('Nie udało się rozszyfrować danych JSON.');
        }

        return $decodedData;
    }
}