<?php
namespace App\Exchange\Infrastructure\Http;

use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Shared\Modules\RestClient\Exceptions\RestClientRequestException;
use App\Shared\Modules\RestClient\Exceptions\RestClientResponseException;
use App\Shared\Modules\RestClient\RestClient;


class NBPCurrencyRateApiClient implements CurrencyRateApiClientInterface
{
    private const ENDPOINT = 'https://api.nbp.pl/api/exchangerates/rates/A/%s/%s/?format=json';

    private RestClient $restClient;

    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * Get exchange rates for a specific date.
     *
     * @param string $currency
     * @param string $date
     * @return ApiCurrencyRate
     */
    public function getExchangeRate(string $currency, string $date): object
    {
        $url = sprintf(self::ENDPOINT, $currency, $date);

        try {
            $response = $this->restClient->get($url);
            return $response->toEntity(ApiCurrencyRate::class);
        } catch (RestClientRequestException | RestClientResponseException $e) {
            throw new \RuntimeException(sprintf('Failed to fetch exchange rate for %s on %s: %s', $currency, $date, $e->getMessage()));
        }
    }
}