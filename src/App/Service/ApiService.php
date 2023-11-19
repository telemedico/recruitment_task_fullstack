<?php

namespace App\Service;

use Exception;


class ApiService
{
    private array $nbp_api;

    public function __construct(
        array $nbp_api
    )
    {
        $this->nbp_api = $nbp_api;
    }

    /**
     * @throws Exception
     */
    public function connectToNBP()
    {
        $apiEndpoint = $this->nbp_api["exchange_rates_all"];

        $apiData = file_get_contents($apiEndpoint);

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