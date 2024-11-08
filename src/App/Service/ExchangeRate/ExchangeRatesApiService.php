<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRatesApiService {
    private $client;
    private $cache;
    private $supportedCurrencies;

    public function __construct(HttpClientInterface $client, CacheInterface $cache, array $supportedCurrencies) {
        $this->client = $client;
        $this->cache = $cache;
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function getExchangeRates(string $date): array {
        $rslt = [
            'effectiveDate' => '',
            'rates' => []
        ];
        try {
            return $this->cache->get('exchange_rates_data_' . $date, function (ItemInterface $item) use($date, $rslt) {
                $item->expiresAfter(600); // 10 minutes
                $response = $this->client->request('GET', 'https://api.nbp.pl/api/exchangerates/tables/A/'.$date.'/?format=json');
                $apiData = $response->toArray();

                if (is_array($apiData) && isset($apiData[0]['effectiveDate']) && isset($apiData[0]['rates']) && isset($apiData[0]['rates'][0])) {
                    
                    $rslt['effectiveDate'] = $apiData[0]['effectiveDate'];
                    foreach($apiData[0]['rates'] as $rate) {
                        if (in_array($rate['code'], $this->supportedCurrencies)) {
                            $rslt['rates'][] = $rate;
                        }
                    }
                }
                return $rslt;
            });
        } catch (\Exception $e) { }
        return $rslt;
    }

    public function getSupportedCurrencies(): array {
        return $this->supportedCurrencies;
    }
}
