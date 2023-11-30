<?php

declare(strict_types=1);

namespace Currencies\Data;

use Currencies\Data\Currency\Rates;

interface CurrencyInterface
{
    public function getCode(): string;
    public function getName(): string;
    public function getIcon(): string;
    public function getRates(): Rates;
}