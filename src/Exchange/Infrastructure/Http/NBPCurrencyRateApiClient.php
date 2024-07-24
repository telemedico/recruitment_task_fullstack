<?php
namespace App\Exchange\Infrastructure\Http;

use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Shared\Modules\RestClient\RestClient;

class NBPCurrencyRateApiClient implements CurrencyRateApiClientInterface
{
    private const ENDPOINT = 'https://api.nbp.pl/api/exchangerates/rates/A/%s/%s/?format=json';

    private RestClient $restClient;

    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    public function getExchangeRate(string $currency, string $date): array
    {
        $url = sprintf(self::ENDPOINT, $currency, $date);
        $response = $this->restClient->get($url);
        return $response->retrieve()->toArray();
    }
}