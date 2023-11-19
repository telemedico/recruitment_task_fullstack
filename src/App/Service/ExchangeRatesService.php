<?php

namespace App\Service;

use function array_filter;
use function array_map;
use function array_merge;
use function explode;
use function gmdate;
use function in_array;
use function json_decode;

/*
 * Serwis, ktory bedzie dostarczal dane dotyczace kursow walut
 * moznaby go udoskonalic aby np. pobieral kurs z dnia poprzedniego jesli nie znajdzie danych na wskazany dzien, dodac cache itd
 */

class ExchangeRatesService {
    private $apiUrl;
    private $currencies;
    private $premium_currencies;
    private $base_sell_margin;
    private $premium_buy_margin;
    private $premium_sell_margin;

    public function __construct() {
        // adres api i inne zmienne konfiguracyjne, ustalane w zmiennych srodowiskowych, w pliku .env
        $this->apiUrl              = $_SERVER['EXCHANGE_RATES_API_URL'];
        $this->premium_currencies  = explode( ',', $_SERVER['EXCHANGE_RATES_PREMIUM_CURRENCIES'] );
        $this->currencies          = array_merge(
            $this->premium_currencies,
            explode( ',', $_SERVER['EXCHANGE_RATES_CURRENCIES'] )
        );
        $this->base_sell_margin    = $_SERVER['EXCHANGE_RATES_BASE_SELL_MARGIN'];
        $this->premium_buy_margin  = $_SERVER['EXCHANGE_RATES_PREMIUM_BUY_MARGIN'];
        $this->premium_sell_margin = $_SERVER['EXCHANGE_RATES_PREMIUM_SELL_MARGIN'];
    }

    private function request( string $date = '' ) {
        $url = $this->apiUrl . '/api/exchangerates/tables/a/?format=json';

        if ( ! empty( $date ) ) {
            $url = $this->apiUrl . '/api/exchangerates/tables/a/' . $date . '/?format=json';
        }

        try {
            return json_decode( file_get_contents( $url ) );
        } catch ( \Exception $exception ) {
            // logowanie bledu
        }

        return [];
    }

    public function get( string $date ): array {
        try {
            $todayRates = $this->request();
            $rates      = gmdate( 'Y-m-d' ) === $date ? $todayRates : $this->request( $date );
            $response   = array_map( function ( $rate ) use ( $todayRates ) {
                if ( in_array( $rate->code, $this->currencies ) ) {
                    // standardowe ustawienia dla walut (CZK,IDR,BRL)
                    $rate->buyPrice  = 0; // nie kupujemy tych walut
                    $rate->sellPrice = $rate->mid + $this->base_sell_margin;

                    if ( in_array( $rate->code, $this->premium_currencies ) ) {
                        // ustawienia dla "walut premium" (EUR,USD)
                        $rate->buyPrice  = $rate->mid - $this->premium_buy_margin;
                        $rate->sellPrice = $rate->mid + $this->premium_sell_margin;
                    }

                    $rate->todayBuyPrice  = 0;
                    $rate->todaySellPrice = 0;
                    $rate->todayMid       = 0;

                    // ustalenie dzisiejszego kursu
                    if ( ! empty( $todayRates ) && ! empty( $todayRates[0]->rates ) ) {
                        $findTodayRate = array_filter( $todayRates[0]->rates,
                            function ( $todayExchangeRate ) use ( $rate ) {
                                return $todayExchangeRate->code === $rate->code;
                            }
                        );

                        if ( $findTodayRate ) {
                            // standardowe ustawienia dla walut (CZK,IDR,BRL)
                            $todayRate            = reset( $findTodayRate );
                            $rate->todayMid       = $todayRate->mid;
                            $rate->todaySellPrice = $todayRate->mid + $this->base_sell_margin;

                            if ( in_array( $rate->code, $this->premium_currencies ) ) {
                                // ustawienia dla "walut premium" (EUR,USD)
                                $rate->todayBuyPrice  = $todayRate->mid - $this->premium_buy_margin;
                                $rate->todaySellPrice = $todayRate->mid + $this->premium_sell_margin;
                            }
                        }
                    }

                    return $rate;
                }
            }, $rates[0]->rates );

            return array_filter( $response );
        } catch ( \Exception $exception ) {
            // logowanie bledu
        }

        return [];
    }
}
