<?php
declare(strict_types=1);

namespace App\Service;

use App\API\Nbp as API;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\DTO\Currency;

class Nbp
{
    private $api;

    private $currencyRequired = [
        'EUR' => ['code' => 'EUR', 'name' => 'Euro', 'buy' => -0.05, 'sell' => 0.07],
        'USD' => ['code' => 'USD', 'name' => 'Dolar amerykański', 'buy' => -0.05, 'sell' => 0.07],
        'CZK' => ['code' => 'CZK', 'name' => 'Korona czeska', 'buy' => null, 'sell' => 0.15],
        'IDR' => ['code' => 'IDR', 'name' => 'Rupia indonezyjska', 'buy' => null, 'sell' => 0.15],
        'BRL' => ['code' => 'BRL', 'name' => 'Real brazylijski', 'buy' => null, 'sell' => 0.15],
    ];

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function getByDate(?string $date = null)
    {
        $cacheKey = $date === 'today' ? (new \DateTime())->format('Y-m-d') : $date;
        $cache = new FilesystemAdapter();
        $value = $cache->get($cacheKey, function () use ($date): array {
            $result = [];
            $allCurrency = $this->api->getTableForDate($date);
            foreach ($allCurrency[0]->rates as $rate) {
                foreach ($this->currencyRequired as $key => $currency) {
                    if ($rate->code === $key) {
                        $result[] = $this->creteCurrency($currency, $rate->mid);
                        break;
                    }
                }
            }
            return $result;
        });
        return $value;
    }

    private function creteCurrency(array $currency, float $mid): array
    {
        $currency['mid'] = $mid;
        $currency['buy'] = $currency['buy'] ? round($currency['buy'] + $mid, 8) : '';
        $currency['sell'] = round($currency['sell'] + $mid, 8);
        return $currency;

    }

    public static function isValidDate(?string $dateString = null): bool
    {
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        return $dateString === 'today' || ($date && $date->format('Y-m-d') === $dateString);
    }
}