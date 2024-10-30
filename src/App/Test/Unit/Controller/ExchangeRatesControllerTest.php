<?php

declare(strict_types = 1);

namespace App\Test\Unit\Controller;

use App\Controller\ExchangeRatesController;
use App\Exception\IncorrectDateException;
use App\ExchangeRate\ApiResponse;
use App\ExchangeRate\CurrencyExchangeClientFactory;
use App\ExchangeRate\CurrencyExchangeClientInterface;
use App\ExchangeRate\DTO\ExchangeRate;
use App\ExchangeRate\DTO\ExchangeRateInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesControllerTest extends TestCase
{
    private const RATES_MOCK_DATA = [
        'USD' => ['rate' => 2.11, 'buyRate' => null, 'sellRate' => null],
        'EUR' => ['rate' => 5.10, 'buyRate' => null, 'sellRate' => null],
    ];

    /**
     * @var CurrencyExchangeClientFactory|MockObject
     */
    private $exchangeRateFactoryMock;

    /**
     * @var CurrencyExchangeClientInterface|MockObject
     */
    private $currencyExchangeClientMock;

    /**
     * @var LoggerInterface|MockObject
     */
    private $loggerMock;

    /**
     * @var ExchangeRatesController
     */
    private $exchangeRatesController;

    public function setUp(): void
    {
        $this->exchangeRateFactoryMock = $this->createMock(CurrencyExchangeClientFactory::class);

        $this->currencyExchangeClientMock = $this->createMock(CurrencyExchangeClientInterface::class);
        $this->currencyExchangeClientMock->method('getRates')
            ->willReturn($this->getRatesMock());

        $this->exchangeRateFactoryMock->method('create')
            ->willReturn($this->currencyExchangeClientMock);

        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->exchangeRatesController = new ExchangeRatesController(
            $this->exchangeRateFactoryMock,
            $this->loggerMock
        );
    }

    public function testGetRates_allSet_returnRatesResponse(): void
    {
        $this->exchangeRateFactoryMock->expects($this->once())
            ->method('create');
        $this->currencyExchangeClientMock->expects($this->once())
            ->method('setDate');
        $this->currencyExchangeClientMock->expects($this->once())
            ->method('getRates');

        $result = $this->exchangeRatesController->getRates('2024-10-15');
        $this->assertEquals($result, new ApiResponse(self::RATES_MOCK_DATA));
        $this->assertInstanceOf(ApiResponse::class, $result);
    }

    public function testGetRates_givenIncorrectDate_returnErrorResponse(): void
    {
        $exception = new IncorrectDateException();

        $this->currencyExchangeClientMock->method('getRates')
            ->willThrowException($exception);

        $this->assertEquals($this->exchangeRatesController->getRates('2023-10-15'), new ApiResponse(
            null,
            "Can't get rates: " . $exception->getMessage(),
            Response::HTTP_BAD_REQUEST
        ));
    }

    public function testGetRates_exceptionThrown_thenReturnErrorResponseAndLogError(): void
    {
        $exception = new Exception('random message');

        $this->currencyExchangeClientMock->method('getRates')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($exception->getMessage(), $exception->getTrace());

        $result = $this->exchangeRatesController->getRates('2023-10-15');

        $this->assertEquals($result, new ApiResponse(
            null,
            "Can't get rates, please try again later.",
            Response::HTTP_BAD_REQUEST
        ));
    }

    /**
     * @return ExchangeRateInterface[]
     * @dataProvider ratesDataProvider
     */
    private function getRatesMock(): array
    {
        $result = [];

        foreach (self::RATES_MOCK_DATA as $currency => $data) {
            $rate = new ExchangeRate();
            $rate->setRate($data['rate']);
            $rate->setCurrency($currency);
            $rate->setSellingRate($data['sellRate']);
            $rate->setBuyingRate($data['buyRate']);

            $result[$currency] = $rate;
        }

        return $result;
    }
}