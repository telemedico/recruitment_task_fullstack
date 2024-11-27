<?php

namespace Unit\Service;

use App\Model\Currency;
use App\Model\CurrencyValue;
use App\Repository\CurrencyRepositoryInterface;
use App\Repository\CurrencyValueRepositoryInterface;
use App\Service\CurrencyPriceCalculatorInterface;
use App\Service\CurrencyPriceViewViewService;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CurrencyPriceViewServiceTest extends TestCase
{
    /** @var CurrencyPriceViewViewService $priceViewService */
    private $priceViewService;

    /** @var CurrencyRepositoryInterface|MockObject */
    private $currencyRepositoryMock;
    /** @var CurrencyValueRepositoryInterface|MockObject */
    private $currencyValueRepositoryMock;
    /** @var CurrencyPriceCalculatorInterface|MockObject */
    private $currencyPriceCalculatorMock;

    protected function setUp(): void
    {
        $this->currencyRepositoryMock = $this->createMock(CurrencyRepositoryInterface::class);
        $this->currencyValueRepositoryMock = $this->createMock(CurrencyValueRepositoryInterface::class);
        $this->currencyPriceCalculatorMock = $this->createMock(CurrencyPriceCalculatorInterface::class);
        $this->priceViewService = new CurrencyPriceViewViewService(
            $this->currencyRepositoryMock,
            $this->currencyValueRepositoryMock,
            $this->currencyPriceCalculatorMock
        );
    }

    // I'd extend the test class with more cases
    public function testGetAllCurrencyPricesByDateWithCorrectDateIsSuccessful(): void
    {
        $date = new DateTime('2024-11-11');
        $currencies = [
            new Currency('USD', 0.15, 0.05),
            new Currency('EUR', 0.15, 0.05)
        ];
        $usdValue = new CurrencyValue('USD', 'dolar', new DateTime(), 4.12);
        $eurValue = new CurrencyValue('EUR', 'euro', new DateTime(), 4.42);

        $this->currencyRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($currencies);
        $this->currencyValueRepositoryMock
            ->method('findByCurrencyCodeAndDate')
            ->withConsecutive(
                ['USD', $this->isInstanceOf(DateTime::class)],
                ['EUR', $this->isInstanceOf(DateTime::class)]
            )->willReturnOnConsecutiveCalls(
                $usdValue, $eurValue
            );
        $this->currencyPriceCalculatorMock->expects($this->exactly(2))
            ->method('calculateSellPrice')
            ->willReturn(1.1);
        $this->currencyPriceCalculatorMock->expects($this->exactly(2))
            ->method('calculateBuyPrice')
            ->willReturn(1.1);

        $result = $this->priceViewService->getAllCurrencyPricesByDate($date);

        $this->assertSame('USD', $result[0]->getCode());
        $this->assertSame(4.12, $result[0]->getNbpPrice());
        $this->assertSame(1.1, $result[0]->getBuyPrice());
        $this->assertSame(1.1, $result[0]->getSellPrice());
        $this->assertSame('EUR', $result[1]->getCode());
        $this->assertSame(4.42, $result[1]->getNbpPrice());
        $this->assertSame(1.1, $result[1]->getBuyPrice());
        $this->assertSame(1.1, $result[1]->getSellPrice());
    }
}