<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Query\View;

use App\Domain\Query\View\ExchangeRatesView;
use App\Domain\Query\View\ExchangeRateView;
use PHPUnit\Framework\TestCase;

final class ExchangeRatesViewTest extends TestCase
{
    public function testJsonSerializeReturnsCorrectStructure(): void
    {
        $latestDate = new \DateTimeImmutable('2024-11-15');
        $userDate = new \DateTimeImmutable('2024-11-10');

        $rate1 = ExchangeRateView::fromArray([
            'currencyCode' => 'USD',
            'currencyName' => 'US dollar',
            'latestBidRate' => 4.10,
            'latestAskRate' => 4.20,
            'latestNbpRate' => 4.15,
            'userDateBidRate' => 4.05,
            'userDateAskRate' => 4.25,
            'userDateNbpRate' => 4.12,
        ]);

        $rate2 = ExchangeRateView::fromArray([
            'currencyCode' => 'EUR',
            'currencyName' => 'Euro',
            'latestBidRate' => 4.50,
            'latestAskRate' => 4.60,
            'latestNbpRate' => 4.55,
            'userDateBidRate' => 4.45,
            'userDateAskRate' => 4.65,
            'userDateNbpRate' => 4.52,
        ]);

        $view = new ExchangeRatesView($latestDate, $userDate, $rate1, $rate2);

        $expectedSerialized = [
            'rates' => [
                $rate1,
                $rate2,
            ],
            'userDate' => '2024-11-10',
            'latestDate' => '2024-11-15',
        ];

        self::assertSame($expectedSerialized, $view->jsonSerialize());
    }

    public function testJsonSerializeHandlesEmptyRates(): void
    {
        $latestDate = new \DateTimeImmutable('2024-11-15');
        $userDate = new \DateTimeImmutable('2024-11-10');

        $view = new ExchangeRatesView($latestDate, $userDate);

        $expectedSerialized = [
            'rates' => [],
            'userDate' => '2024-11-10',
            'latestDate' => '2024-11-15',
        ];

        self::assertSame($expectedSerialized, $view->jsonSerialize());
    }
}
