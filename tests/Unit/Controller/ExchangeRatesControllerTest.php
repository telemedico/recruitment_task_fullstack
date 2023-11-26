<?php

namespace Unit\Controller;

use App\Controller\ExchangeRatesController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesControllerTest extends WebTestCase
{
    const URL = '/api/exchange-rates';

    /**
     * Test if getExchangeRates method return status code HTTP_BAD_REQUEST with invalid date. 
     */
    public function testGetExchangeRatesWithInvalidDate(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL, ['date' => 'hghgg']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    /**
     * Test if getExchangeRates method return status code HTTP_OK with valid date. 
     */
    public function testGetExchangeRatesWithValidDate(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL, ['date' => '2023-01-02']);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test if prepareExchangeRatesItem method returns correct values ​​for supported currencies. 
     */
    public function testCorrectValuesWithSupportedCurrencies(): void
    {
        $testItem = [
            'code' => 'EUR',
            'name' => 'euro',
            'midRate' => 0
        ];

        $supportedCurrencies = ['EUR'];

        $expectedItem = [
            'code' => 'EUR',
            'name' => 'euro',
            'midRate' => 0,
            'purchase' => $_ENV['PURCHASE_MARGIN'],
            'sell' => $_ENV['SALES_MARGIN']
        ];

        $exchangeRatesController = new ExchangeRatesController;
        $returnItem = $exchangeRatesController->prepareExchangeRatesItem($testItem, $supportedCurrencies);
        $this->assertEquals($returnItem, $expectedItem);
    }

    /**
     * Test if prepareExchangeRatesItem method returns correct values ​​for not supported currencies. 
     */
    public function testCorrectValuesWithNotSupportedCurrencies(): void
    {
        $testItem = [
            'code' => 'EUR',
            'name' => 'euro',
            'midRate' => 0
        ];

        $supportedCurrencies = [];

        $expectedItem = [
            'code' => 'EUR',
            'name' => 'euro',
            'midRate' => 0,
            'purchase' => null,
            'sell' => $_ENV['UNSUPPORTED_SALES_MARGIN']
        ];

        $exchangeRatesController = new ExchangeRatesController;
        $returnItem = $exchangeRatesController->prepareExchangeRatesItem($testItem, $supportedCurrencies);
        $this->assertEquals($returnItem, $expectedItem);
    }
}