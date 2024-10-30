<?php

declare(strict_types = 1);

namespace App\ExchangeRate\Http;

use App\Constant\Formats;
use App\Exception\IncorrectDateException;
use App\ExchangeRate\DTO\ExchangeRate;
use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\CurrencyExchangeClientInterface;
use App\ExchangeRate\UsedCurrenciesProvider;
use DateTime;
use DateTimeZone;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NBPRestClient extends AbstractCurrencyExchangeClient implements CurrencyExchangeClientInterface
{
    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        string $apiEndpoint,
        UsedCurrenciesProvider $usedCurrenciesProvider,
        HttpClientInterface $httpClient,
        CacheInterface $cache
    ) {
        $this->apiEndpoint = $apiEndpoint;
        $this->httpClient = $httpClient;
        $this->cache = $cache;

        parent::__construct($usedCurrenciesProvider);
    }

    /**
     * @return ExchangeRateInterface[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface|InvalidArgumentException
     * @throws IncorrectDateException
     */
    public function getRates(): array
    {
        if (!$this->validateDate()) {
            throw new IncorrectDateException();
        }

        $apiResponse = $this->getApiResponse();

        return $this->prepareRates($apiResponse);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     */
    private function getApiResponse(): array
    {
        return $this->cache->get($this->getCacheKey(), function (ItemInterface $item) {
            $item->expiresAfter($this->getCacheExpires());

            $response = $this->httpClient->request(
                'GET',
                $this->prepareApiUrl()
            )->toArray();

            return $response[0]['rates'] ?? [];
        });
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
                $rate->setCurrency((string)$rateData['code']);

                $result[$rate->getCurrency()] = $rate;
            }
        }

        return $this->modifyRates($result);
    }

    private function getCacheKey(): string
    {
        return implode('-', [
            'NBPRestClient',
            implode('-', $this->usedCurrencies),
            $this->date->format(Formats::DEFAULT_DATE_FORMAT)
        ]);
    }

    /**
     * @return int|null
     */
    private function getCacheExpires(): ?int
    {
        $today = new DateTime();
        $isToday = $this->date->format(Formats::DEFAULT_DATE_FORMAT)
            === $today->format(Formats::DEFAULT_DATE_FORMAT);

        $today->setTimezone(new DateTimeZone('Europe/Warsaw'));
        $hour = $today->format('H');

        /**
         * Jako, ze zgodnie z trescia zadania NBP aktualizuje stawki o 12 - requesty przed 12 maja ograniczony czas
         * egzystowania do 12, jak jest po 12 - cache generujemy od nowa i zapisujemy bez limitu
         */
        if ($isToday && $hour < 12) {
            return $today->setTime(12, 0)->getTimestamp();
        }

        /**
         * Zapytania z wszystkich innych dni maja nieograniczony czas
         */
        return null;
    }
}