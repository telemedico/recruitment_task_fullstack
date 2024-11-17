<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Query\View;

use App\Domain\Query\View\ExchangeRateView;
use PHPUnit\Framework\TestCase;

final class ExchangeRateViewTest extends TestCase
{
    public function testFromArrayCreatesInstanceCorrectly(): void
    {
        $data = [
            'currencyCode' => 'USD',
            'currencyName' => 'US dollar',
            'latestBidRate' => 4.10,
            'latestAskRate' => 4.20,
            'latestNbpRate' => 4.15,
            'userDateBidRate' => 4.05,
            'userDateAskRate' => 4.25,
            'userDateNbpRate' => 4.12,
        ];

        $exchangeRateView = ExchangeRateView::fromArray($data);

        self::assertInstanceOf(ExchangeRateView::class, $exchangeRateView);

        $exchangeRateViewData = $exchangeRateView->jsonSerialize();

        self::assertSame('USD', $exchangeRateViewData['currencyCode']);
        self::assertSame('US dollar', $exchangeRateViewData['currencyName']);
        self::assertSame(4.10, $exchangeRateViewData['latestBidRate']);
        self::assertSame(4.20, $exchangeRateViewData['latestAskRate']);
        self::assertSame(4.15, $exchangeRateViewData['latestNbpRate']);
        self::assertSame(4.05, $exchangeRateViewData['userDateBidRate']);
        self::assertSame(4.25, $exchangeRateViewData['userDateAskRate']);
        self::assertSame(4.12, $exchangeRateViewData['userDateNbpRate']);
    }

    public function testFromArrayHandlesMissingValues(): void
    {
        $data = [
            'currencyCode' => 'EUR',
            'currencyName' => 'Euro',
        ];

        $exchangeRateView = ExchangeRateView::fromArray($data);

        $expected = [
            'currencyCode' => 'EUR',
            'currencyName' => 'Euro',
            'latestBidRate' => null,
            'latestAskRate' => null,
            'latestNbpRate' => null,
            'userDateBidRate' => null,
            'userDateAskRate' => null,
            'userDateNbpRate' => null,
        ];

        self::assertSame($expected, $exchangeRateView->jsonSerialize());
    }

    public function testJsonSerializeReturnsCorrectStructure(): void
    {
        $data = [
            'currencyCode' => 'GBP',
            'currencyName' => 'British Pound',
            'latestBidRate' => 5.10,
            'latestAskRate' => 5.20,
            'latestNbpRate' => 5.15,
            'userDateBidRate' => 5.05,
            'userDateAskRate' => 5.25,
            'userDateNbpRate' => 5.12,
        ];

        $exchangeRateView = ExchangeRateView::fromArray($data);

        $expected = [
            'currencyCode' => 'GBP',
            'currencyName' => 'British Pound',
            'latestBidRate' => 5.10,
            'latestAskRate' => 5.20,
            'latestNbpRate' => 5.15,
            'userDateBidRate' => 5.05,
            'userDateAskRate' => 5.25,
            'userDateNbpRate' => 5.12,
        ];

        self::assertSame($expected, $exchangeRateView->jsonSerialize());
    }
}
