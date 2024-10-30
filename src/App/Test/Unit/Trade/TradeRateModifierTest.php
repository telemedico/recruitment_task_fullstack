<?php

declare(strict_types = 1);

namespace App\Test\Unit\Trade;

use App\Exception\NoCalculationStrategyException;
use App\ExchangeRate\DTO\ExchangeRate;
use App\ExchangeRate\DTO\ExchangeRateInterface;
use App\ExchangeRate\Trade\Strategies\BuyingStrategyOne;
use App\ExchangeRate\Trade\Strategies\DefaultBuyingStrategy;
use App\ExchangeRate\Trade\Strategies\DefaultSellingStrategy;
use App\ExchangeRate\Trade\Strategies\SellingStrategyOne;
use App\ExchangeRate\Trade\TradeRateModifier;
use PHPUnit\Framework\TestCase;

class TradeRateModifierTest extends TestCase
{
    /**
     * @var TradeRateModifier
     */
    private $tradeRateModifier;

    public function setUp(): void
    {
        $buyingRateCalcStrategies = [
            'USD' => new BuyingStrategyOne(),
            'EUR' => new DefaultBuyingStrategy()
        ];

        $sellRateCalcStrategies = [
            'USD' => new DefaultSellingStrategy(),
            'EUR' => new SellingStrategyOne()
        ];

        $this->tradeRateModifier = new TradeRateModifier(
            $buyingRateCalcStrategies,
            $sellRateCalcStrategies
        );
    }

    /**
     * @dataProvider ratesDataProvider
     */
    public function testModify_existingStrategy_modifyRate(
        ExchangeRateInterface $rate,
        ?float $expectedBuyingRate,
        ?float $expectedSellingRate
    ): void {
        $rate = $this->tradeRateModifier->modify($rate);

        $this->assertEquals($expectedBuyingRate, $rate->getBuyingRate());
        $this->assertEquals($expectedSellingRate, $rate->getSellingRate());
    }

    public function testModify_noExistingStrategy_throwException(): void
    {
        $rate = new ExchangeRate();
        $rate->setCurrency('XXX');

        $this->expectException(NoCalculationStrategyException::class);
        $this->expectExceptionMessage("Calculation strategy for {$rate->getCurrency()} doesn't exist.");

        $this->tradeRateModifier->modify($rate);
    }

    public function testModifyMany_existingStrategy_modifyRates(): void
    {
        $result = $this->tradeRateModifier->modifyMany($this->ratesMock());

        foreach ($result as $k => $rate) {
            $this->assertEquals($this->ratesDataProvider()[$k]['buyingRate'], $rate->getBuyingRate());
            $this->assertEquals($this->ratesDataProvider()[$k]['sellingRate'], $rate->getSellingRate());
        }
    }

    public function ratesDataProvider(): array
    {
        $rates = $this->ratesMock();

        return [
            ['rate' => $rates[0], 'buyingRate' => 3.28, 'sellingRate' => 3.48],
            ['rate' => $rates[1], 'buyingRate' => null, 'sellingRate' => 2.2]
        ];
    }

    private function ratesMock(): array
    {
        $rate1 = new ExchangeRate();
        $rate1->setRate(3.33);
        $rate1->setCurrency('USD');

        $rate2 = new ExchangeRate();
        $rate2->setRate(2.13);
        $rate2->setCurrency('EUR');

        return [$rate1, $rate2];
    }
}