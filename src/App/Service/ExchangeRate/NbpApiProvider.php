<?php

namespace App\Service\ExchangeRate;

use App\Interface\ExchangeRate\ExchangeRateProviderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpApiProvider implements ExchangeRateProviderInterface 
{
    private const BASE_URL = 'https://api.nbp.pl/api';
    private const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
    
    private $logPath;
    private $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->logPath = __DIR__ . '/../../../../var/log/nbp.log';
    }
    
    public function getRatesForDate(\DateTimeInterface $date): array
    {
        $this->log("Starting request for date: " . $date->format('Y-m-d'));

        try {
            $today = new \DateTime();
            $requestedDate = clone $date;
            
            while ($requestedDate->format('N') >= 6) {
                $requestedDate->modify('-1 day');
                $this->log("Weekend detected, adjusted to: " . $requestedDate->format('Y-m-d'));
            }

            $oldestDate = new \DateTime('2023-01-01');
            if ($requestedDate < $oldestDate) {
                $this->log("Date too old, using oldest available: 2023-01-01");
                $requestedDate = $oldestDate;
            }
            
            if ($requestedDate > $today) {
                $endpoint = '/exchangerates/tables/A/last/?format=json';
                $this->log("Future date, using latest rates endpoint");
            } else if ($requestedDate->format('Y-m-d') === $today->format('Y-m-d')) {
                $endpoint = '/exchangerates/tables/A/last/?format=json';
                $this->log("Today's date, using latest rates endpoint");
            } else {
                $formattedDate = $requestedDate->format('Y-m-d');
                $endpoint = sprintf('/exchangerates/tables/A/%s/?format=json', $formattedDate);
                $this->log("Using historical rates endpoint for date: " . $formattedDate);
            }
            
            $this->log("Making request to: " . self::BASE_URL . $endpoint);
            
            $response = $this->httpClient->request(
                'GET',
                self::BASE_URL . $endpoint,
                [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]
            );
            
            $data = json_decode($response->getContent(), true);
            $this->log("NBP API Response: " . json_encode($data));
            
            if (!isset($data[0]['rates']) || !isset($data[0]['effectiveDate'])) {
                throw new \Exception('Invalid response format from NBP API');
            }
            
            $filteredRates = array_values(array_filter(
                $data[0]['rates'],
                function($rate) {
                    return in_array($rate['code'], self::SUPPORTED_CURRENCIES);
                }
            ));
            
            $result = [
                'rates' => $filteredRates,
                'effectiveDate' => $data[0]['effectiveDate']
            ];
            
            $this->log("Returning rates for date " . $date->format('Y-m-d') . ": " . json_encode($result));
            
            return $result;
            
        } catch (\Exception $e) {
            $this->log("Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function log($message)
    {
        file_put_contents($this->logPath, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }
}