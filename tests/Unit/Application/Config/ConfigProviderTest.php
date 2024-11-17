<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Config;

use App\Application\Config\ConfigProvider;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    protected function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function testGetAvailableCurrencies(): void
    {
        $expectedCurrencies = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

        self::assertSame($expectedCurrencies, $this->configProvider->getAvailableCurrencies());
    }

    /**
     * @dataProvider bidShiftDataProvider
     */
    public function testGetBidShiftForCurrency(string $currency, float $expectedShift): void
    {
        self::assertSame($expectedShift, $this->configProvider->getBidShiftForCurrency($currency));
    }

    public function bidShiftDataProvider(): array
    {
        return [
            ['EUR', 0.05],
            ['USD', 0.05],
            ['CZK', 0.00],
            ['IDR', 0.00],
            ['BRL', 0.00],
        ];
    }

    public function testGetBidShiftForCurrencyThrowsExceptionForInvalidCurrency(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unexpected currency.');

        $this->configProvider->getBidShiftForCurrency('INVALID');
    }

    /**
     * @dataProvider askShiftDataProvider
     */
    public function testGetAskShiftForCurrency(string $currency, float $expectedShift): void
    {
        self::assertSame($expectedShift, $this->configProvider->getAskShiftForCurrency($currency));
    }

    public function askShiftDataProvider(): array
    {
        return [
            ['EUR', 0.07],
            ['USD', 0.07],
            ['CZK', 0.15],
            ['IDR', 0.15],
            ['BRL', 0.15],
        ];
    }

    public function testGetAskShiftForCurrencyThrowsExceptionForInvalidCurrency(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unexpected currency.');

        $this->configProvider->getAskShiftForCurrency('INVALID');
    }

    /**
     * @dataProvider bidAvailabilityDataProvider
     */
    public function testIsBidAvailableForCurrency(string $currency, bool $expectedAvailability): void
    {
        self::assertSame($expectedAvailability, $this->configProvider->isBidAvailableForCurrency($currency));
    }

    public function bidAvailabilityDataProvider(): array
    {
        return [
            ['EUR', true],
            ['USD', true],
            ['CZK', false],
            ['IDR', false],
            ['BRL', false],
        ];
    }

    /**
     * @dataProvider askAvailabilityDataProvider
     */
    public function testIsAskAvailableForCurrency(string $currency, bool $expectedAvailability): void
    {
        self::assertSame($expectedAvailability, $this->configProvider->isAskAvailableForCurrency($currency));
    }

    public function askAvailabilityDataProvider(): array
    {
        return [
            ['EUR', true],
            ['USD', true],
            ['CZK', true],
            ['IDR', true],
            ['BRL', true],
        ];
    }

    public function testIsBidAvailableForInvalidCurrency(): void
    {
        self::assertFalse($this->configProvider->isBidAvailableForCurrency('INVALID'));
    }

    public function testIsAskAvailableForInvalidCurrency(): void
    {
        self::assertFalse($this->configProvider->isAskAvailableForCurrency('INVALID'));
    }
}
