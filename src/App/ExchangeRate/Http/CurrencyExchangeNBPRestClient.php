<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Http;

use App\Constant\Formats;
use App\ExchangeRate\DTO\ExchangeRate;
use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\CurrencyExchangeClientInterface;
use App\ExchangeRate\UsedCurrenciesProvider;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeNBPRestClient extends AbstractCurrencyExchangeClient implements CurrencyExchangeClientInterface
{
    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(
        string $apiEndpoint,
        UsedCurrenciesProvider $usedCurrenciesProvider,
        HttpClientInterface $httpClient
    ) {
        $this->apiEndpoint = $apiEndpoint;
        $this->httpClient = $httpClient;

        parent::__construct($usedCurrenciesProvider);
    }

    /**
     * @return ExchangeRateInterface[]
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getRates(): array
    {
        if (!$this->validateDate()) {
            throw new \Exception('data z dupy');
        }

        $apiResponse = $this->getApiResponse();

        return $this->prepareRates($apiResponse);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getApiResponse(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->prepareApiUrl()
        )->toArray();

        return $response[0]['rates'] ?? [];
    }

    private function prepareApiUrl(): string
    {
        $date = $this->date->format(Formats::DEFAULT_DATE_FORMAT);

        return str_replace('{DATE}', $date, $this->apiEndpoint);
    }

    /**
     * @param array $apiResult
     * @return ExchangeRateInterface[]
     */
    private function prepareRates(array $apiResult): array
    {
        $result = [];

        foreach ($apiResult as $rateData) {
            if (in_array(strtoupper($rateData['code']), $this->usedCurrencies)) {
                $rate = new ExchangeRate();
                $rate->setRate((float)$rateData['mid']);
                $rate->setDate($this->date);
                $rate->setCurrency((string)$rateData['code']);

                $result[$rate->getCurrency()] = $rate;
            }
        }

        return $this->modifyRates($result);
    }
}