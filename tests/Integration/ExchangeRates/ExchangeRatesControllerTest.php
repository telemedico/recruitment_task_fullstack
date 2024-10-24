<?php

namespace Integration\ExchangeRates;

use App\Controller\ExchangeRatesController;
use App\CurrencyRateProviders\CurrencyRateProviderInterface;
use App\Dto\CurrencyRateDto;
use App\Dto\CurrencyRatesDto;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExchangeRatesControllerTest extends TestCase
{
    private $currencyRateProviderMock;
    private $currencyRatesDtoMock;
    private $currencyRateDtoMock;

    public function testInvokeSuccess(): void
    {
        $date = '2024-10-20';
        $formattedDate = '2024-10-20T00:00:00+00:00';

        // Set up DTO mocks
        $this->currencyRatesDtoMock->method('getDate')->willReturn(new DateTime($date));
        $this->currencyRatesDtoMock->method('getRates')->willReturn([$this->currencyRateDtoMock]);
        $this->currencyRateProviderMock->method('getCurrencyRates')->willReturn($this->currencyRatesDtoMock);
        $this->currencyRateDtoMock->method('toArray')->willReturn(['currency' => 'USD', 'rate' => 1.0]);

        // Initialize the controller
        $controller = new ExchangeRatesController();
        $response = $controller->__invoke($this->currencyRateProviderMock, $date);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals($formattedDate, $data['date']);
        $this->assertCount(1, $data['rates']);
        $this->assertEquals(['currency' => 'USD', 'rate' => 1.0], $data['rates'][0]);
    }

    public function testInvokeWithInvalidDateThrowsException(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Invalid date.');

        $controller = new ExchangeRatesController();
        $controller->__invoke($this->currencyRateProviderMock, 'invalid-date');
    }

    public function testInvokeWithOldDateThrowsException(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Date is too old.');

        $controller = new ExchangeRatesController();
        $controller->__invoke($this->currencyRateProviderMock, '2022-12-31');
    }

    public function testInvokeWithNullDate(): void
    {
        $date = null;

        $this->currencyRatesDtoMock->method('getDate')->willReturn(new DateTime('2024-10-20'));
        $this->currencyRatesDtoMock->method('getRates')->willReturn([$this->currencyRateDtoMock]);
        $this->currencyRateProviderMock->method('getCurrencyRates')->willReturn($this->currencyRatesDtoMock);
        $this->currencyRateDtoMock->method('toArray')->willReturn(['currency' => 'USD', 'rate' => 1.0]);

        $controller = new ExchangeRatesController();
        $response = $controller->__invoke($this->currencyRateProviderMock, $date);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals('2024-10-20T00:00:00+00:00', $data['date']);
        $this->assertCount(1, $data['rates']);
        $this->assertEquals(['currency' => 'USD', 'rate' => 1.0], $data['rates'][0]);
    }

    protected function setUp(): void
    {
        $this->currencyRateProviderMock = $this->createMock(CurrencyRateProviderInterface::class);
        $this->currencyRatesDtoMock = $this->createMock(CurrencyRatesDto::class);
        $this->currencyRateDtoMock = $this->createMock(CurrencyRateDto::class);
    }
}
