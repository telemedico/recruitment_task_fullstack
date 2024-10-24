<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CurrencyRateDto;
use App\Enum\CurrencyEnum;
use InvalidArgumentException;

class CurrencyRateCalculator
{
    public function __invoke(string $currency, string $name, float $rate): CurrencyRateDto
    {
        if (!CurrencyEnum::supports($currency)) {
            throw new InvalidArgumentException(sprintf('Invalid currency "%s"', $currency));
        }

        switch ($currency) {
            case CurrencyEnum::EUR:
            case CurrencyEnum::USD:
                return new CurrencyRateDto(
                    $currency,
                    $name,
                    $rate - 0.05,
                    $rate + 0.07
                );
            default:
                return new CurrencyRateDto(
                    $currency,
                    $name,
                    null,
                    $rate + 0.15
                );
        }
    }
}