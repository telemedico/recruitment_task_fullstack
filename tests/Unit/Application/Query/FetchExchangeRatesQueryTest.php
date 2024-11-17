<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query;

use App\Application\Api\NBP\NbpApiInterface;
use App\Application\Config\ConfigProviderInterface;
use App\Application\Query\FetchExchangeRatesQuery;
use App\Domain\Query\Filter\ExchangeRatesFilter;
use App\Domain\Query\View\ExchangeRatesView;
use App\Domain\Query\View\ExchangeRateView;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FetchExchangeRatesQueryTest extends TestCase
{
    /**
     * @var NbpApiInterface&MockObject
     */
    private $api;

    /**
     * @var ConfigProviderInterface&MockObject
     */
    private $configProvider;

    /**
     * @var FetchExchangeRatesQuery
     */
    private $query;

    protected function setUp(): void
    {
        $this->api = $this->createMock(NbpApiInterface::class);
        $this->configProvider = $this->createMock(ConfigProviderInterface::class);

        $this->query = new FetchExchangeRatesQuery($this->api, $this->configProvider);
    }

    public function testQueryReturnsExchangeRatesView(): void
    {
        $userDate = new \DateTimeImmutable('2024-11-10');
        $latestDate = new \DateTimeImmutable('2024-11-17');
        $filter = new ExchangeRatesFilter($userDate, $latestDate);

        $this->configProvider
            ->method('getAvailableCurrencies')
            ->willReturn(['USD', 'EUR']);
        $this->configProvider
            ->method('isBidAvailableForCurrency')
            ->willReturnMap([
                ['USD', true],
                ['EUR', true],
            ]);
        $this->configProvider
            ->method('isAskAvailableForCurrency')
            ->willReturnMap([
                ['USD', true],
                ['EUR', true],
            ]);
        $this->configProvider
            ->method('getBidShiftForCurrency')
            ->willReturnMap([
                ['USD', 0.05],
                ['EUR', 0.20],
            ]);
        $this->configProvider
            ->method('getAskShiftForCurrency')
            ->willReturnMap([
                ['USD', 0.02],
                ['EUR', 0.10],
            ]);

        $this->api
            ->expects(self::exactly(2))
            ->method('fetchExchangeRatesForDate')
            ->willReturnOnConsecutiveCalls(
                [
                    ['rates' => [
                        ['code' => 'USD', 'currency' => 'US dollar', 'mid' => 4.0],
                        ['code' => 'EUR', 'currency' => 'Euro', 'mid' => 4.50],
                    ]],
                ],
                [
                    ['rates' => [
                        ['code' => 'USD', 'currency' => 'US dollar', 'mid' => 4.12],
                        ['code' => 'EUR', 'currency' => 'Euro', 'mid' => 4.40],
                    ]],
                ]
            );

        $result = $this->query->query($filter);

        self::assertInstanceOf(ExchangeRatesView::class, $result);

        $arrayResult = $result->jsonSerialize();

        self::assertSame('2024-11-17', $arrayResult['latestDate']);
        self::assertSame('2024-11-10', $arrayResult['userDate']);
        self::assertCount(2, $arrayResult['rates']);

        $usdRate = $arrayResult['rates'][0];
        self::assertInstanceOf(ExchangeRateView::class, $usdRate);

        $usdRateArray = $usdRate->jsonSerialize();
        self::assertSame('USD', $usdRateArray['currencyCode']);
        self::assertSame('US dollar', $usdRateArray['currencyName']);
        self::assertSame(3.95, $usdRateArray['userDateBidRate']);
        self::assertSame(4.02, $usdRateArray['userDateAskRate']);
        self::assertSame(4.00, $usdRateArray['userDateNbpRate']);
        self::assertSame(4.07, $usdRateArray['latestBidRate']);
        self::assertSame(4.14, $usdRateArray['latestAskRate']);
        self::assertSame(4.12, $usdRateArray['latestNbpRate']);

        $eurRate = $arrayResult['rates'][1];
        self::assertInstanceOf(ExchangeRateView::class, $eurRate);

        $eurRateArray = $eurRate->jsonSerialize();
        self::assertSame('EUR', $eurRateArray['currencyCode']);
        self::assertSame('Euro', $eurRateArray['currencyName']);
        self::assertSame(4.30, $eurRateArray['userDateBidRate']);
        self::assertSame(4.60, $eurRateArray['userDateAskRate']);
        self::assertSame(4.50, $eurRateArray['userDateNbpRate']);
        self::assertSame(4.20, $eurRateArray['latestBidRate']);
        self::assertSame(4.50, $eurRateArray['latestAskRate']);
        self::assertSame(4.40, $eurRateArray['latestNbpRate']);
    }
}
