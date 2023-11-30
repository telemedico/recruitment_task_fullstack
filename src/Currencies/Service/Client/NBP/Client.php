<?php

declare(strict_types=1);

namespace Currencies\Service\Client\NBP;

use Currencies\Service\Client\ClientInterface;
use Currencies\Service\Client\NBP\Response\Currency;
use Currencies\Service\Client\Response\CurrencyInterface as CurrencyResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements ClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $dataSourceUrl;

    /**
     * @var string[]
     */
    private $availableCurrencyCodes;

    public function __construct(
        HttpClientInterface $client,
        string $currencyDataSourceUrl,
        array $currencyRates
    ) {
        $this->dataSourceUrl = $currencyDataSourceUrl;
        $this->client = $client;
        $this->availableCurrencyCodes = array_keys($currencyRates);
    }

    /**
     * @return CurrencyResponseInterface[]
     */
    public function getCurrencies(\DateTime $dateTime): array
    {
        $url = $this->getFullUrl($dateTime->format('Y-m-d'));

        $response = $this->client->request(Request::METHOD_GET, $url);

        if ($response->getStatusCode() === 404) {
            return [];
        }

        $data = json_decode($response->getContent());

        return $this->createResponse($data);
    }

    private function createResponse(array $data): array
    {
        $response = [];
        foreach (current($data)->rates as $rate) {
            $code = strtolower($rate->code);
            if (!in_array($code, $this->availableCurrencyCodes)) {
                continue;
            }

            $response[$code] = new Currency(
                ucfirst($rate->currency),
                $code,
                $rate->mid
            );
        }

        return $response;
    }

    private function getFullUrl(string $dateAsString)
    {
        return sprintf(
            '%s/%s?format=json',
            $this->dataSourceUrl,
            $dateAsString
        );
    }
}