<?php

declare(strict_types=1);

namespace App\Exchange\Infrastructure\Http;

use App\Exchange\Domain\Service\CurrencyRateApiClientInterface;
use App\Shared\Modules\RestClient\RestClient;

class NBPCurrencyRateApiClient implements CurrencyRateApiClientInterface
{
    private const ENDPOINT = 'https://api.nbp.pl/api/exchangerates/rates/A/%s/%s/?format=json';

    /**
     * @var RestClient
     */
    private $restClient;

    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * Get exchange rates for a specific date.
     *
     * @return ApiCurrencyRate
     */
    public function getExchangeRate(string $currency, \DateTimeImmutable $date): object
    {
        $url = sprintf(self::ENDPOINT, $currency, $date->format('Y-m-d'));

        $response = $this->restClient->get($url);

        return $response->toEntity(ApiCurrencyRate::class);
    }
}
