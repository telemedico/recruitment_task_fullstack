<?php

namespace App\Services;

use App\Dto\CurrencyDto;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Collections\ArrayCollection;

class NationalBankService
{
    const CACHE_EXPIRATION = 86400;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var array
     */
    private $currencies;

    /**
     * @var FilesystemAdapter
     */
    private $cache;

    /**
     * @param HttpClientInterface $httpClient
     * @param array $currencies
     */
    public function __construct(HttpClientInterface $httpClient, array $currencies)
    {
        $this->httpClient = $httpClient;
        $this->currencies = $currencies;
        $this->cache = new FilesystemAdapter();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getTables(string $date): ArrayCollection
    {
        $cacheKey = $this->generateCacheKey($date);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        $action = "exchangerates/tables/A/{$date}?format=json";

        try {
            $response = $this->httpClient->request('GET', $action);
            $rates = $response->toArray();
            $rates = array_shift($rates);

            $ratesCollection = $this->makeCollection($rates["rates"]);
            $cacheItem->set($ratesCollection);
            $cacheItem->expiresAfter(self::CACHE_EXPIRATION);
            $this->cache->save($cacheItem);

            return $ratesCollection;
        } catch (TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface | DecodingExceptionInterface | ClientExceptionInterface $e) {
            throw new RuntimeException('Failed to fetch exchange rates.', 0, $e);
        }
    }

    /**
     * @param ArrayCollection $table
     * @param string $code
     * @return CurrencyDto|null
     */
    public function getByCurrency(ArrayCollection $table, string $code): ?CurrencyDto
    {
        return $table->filter(function (CurrencyDto $dto) use ($code) {
            return $dto->getCode() === $code;
        })->first();
    }

    /**
     * @param array $rates
     * @return ArrayCollection
     */
    private function makeCollection(array $rates): ArrayCollection
    {
        $currencyRateObjects = new ArrayCollection();
        $allowedCurrencies = array_keys($this->currencies);

        foreach ($rates as $rate) {
            if (in_array($rate['code'], $allowedCurrencies)) {
                $purchase = $rate['mid'] - $this->currencies[$rate['code']]['purchase'];
                $sale = $rate['mid'] + $this->currencies[$rate['code']]['sale'];
                $currencyRateObjects->add(new CurrencyDto($rate['currency'], $rate['code'], (string) $rate['mid'], (string) $purchase, (string) $sale));
            }

        }
        return $currencyRateObjects;
    }

    /**
     * @param string $date
     * @return string
     */
    private function generateCacheKey(string $date): string
    {
        return "exchange_rates_{$date}";
    }
}
