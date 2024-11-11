<?php

declare(strict_types=1);

namespace App\Test\Processor;

use App\Config\RatesConfigProvider;
use App\Processor\RateProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RateProcessorTest extends TestCase
{
    private const RELATIVE_RATES = [
        [
            'currencies' => ['EUR', 'USD'],
            'buy' => -0.05,
            'sell' => 0.07,
        ],
        [
            'currencies' => ['CZK', 'IDR', 'BRL'],
            'buy' => null,
            'sell' => 0.15,
        ]
    ];

    /**
     * @var RatesConfigProvider|MockObject
     */
    private $ratesConfigProvider;

    /**
     * @var RateProcessor
     */
    private $rateProcessor;

    public function setUp(): void
    {
        $this->ratesConfigProvider = $this->createMock(RatesConfigProvider::class);
        $this->ratesConfigProvider
            ->method('getRelativeRates')
            ->willReturn(self::RELATIVE_RATES);

        $this->rateProcessor = new RateProcessor($this->ratesConfigProvider);

        parent::setUp();
    }

    public function testBuySellRate(): void
    {
        $returned = $this->rateProcessor->execute([
            'code' => 'EUR',
            'mid' => 1.0
        ]);

        self::assertEquals(0.95, $returned->getBuy());
        self::assertEquals(1.07, $returned->getSell());
    }

    public function testBuyOnlyRate(): void
    {
        $returned = $this->rateProcessor->execute([
            'code' => 'CZK',
            'mid' => 1.0
        ]);

        self::assertEquals(null, $returned->getBuy());
        self::assertEquals(1.15, $returned->getSell());
    }

    public function testNotHandledRate(): void
    {
        $returned = $this->rateProcessor->execute([
            'code' => 'JPY',
            'mid' => 0.027
        ]);

        self::assertEquals(null, $returned->getBuy());
        self::assertEquals(null, $returned->getSell());
    }
}