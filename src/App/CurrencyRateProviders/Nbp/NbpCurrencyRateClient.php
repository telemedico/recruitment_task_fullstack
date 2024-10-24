<?php

declare(strict_types=1);

namespace App\CurrencyRateProviders\Nbp;

use DateTime;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpCurrencyRateClient
{
    /**
     * @var HttpClientInterface
     */
    private $nbpClient;

    public function __construct(
        HttpClientInterface $nbpClient
    )
    {
        $this->nbpClient = $nbpClient;
    }

    public function getCurrencyRates(?DateTime $date = null): array
    {
        $response = $this->nbpClient->request(
            'GET',
            sprintf(
                "/api/exchangerates/tables/A/%s?format=json",
                $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $date
            )
        );

        try {
            $decoded = json_decode($response->getContent(), true);
        } catch (HttpExceptionInterface $e) {
            throw new NbpServiceException('Invalid response. No data found.');
        }

        if (!isset($decoded[0]['rates'])) {
            throw new NbpServiceException('Invalid response. No data found.');
        }

        return $decoded[0];
    }
}