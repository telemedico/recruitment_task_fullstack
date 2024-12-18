<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

final class ExchangeRateConfig
{
    public const MAJOR_CURRENCIES = ['EUR', 'USD'];
    public const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
    
    public const MAJOR_BUY_MARGIN = 0.05;
    public const MAJOR_SELL_MARGIN = 0.07;
    public const OTHER_SELL_MARGIN = 0.15;
}