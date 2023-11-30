<?php

declare(strict_types=1);

namespace Currencies\Service\Currency;

class Calculator
{
    /**
     * @var array
     */
    private $currencyConfig;

    public function __construct(array $currencyConfig) {
        $this->currencyConfig = $currencyConfig;
    }

    public function getSellPrice(float $value): ?float {
        return $this->calculate($value, $this->currencyConfig['sell'] ?? 0);
    }

    public function getBuyPrice(float $value): ?float {
        return $this->calculate($value, -($this->currencyConfig['buy'] ?? 0));
    }

    private function calculate(float $a, float $b): ?float {
        if ($b == 0) {
            return null;
        }

        return $a + $b;
    }
}