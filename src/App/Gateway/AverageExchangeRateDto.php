<?php

declare(strict_types=1);

namespace App\Gateway;

final class AverageExchangeRateDto
{
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $mid;

    public function __construct(string $currency, string $code, string $mid)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->mid = $mid;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function mid(): string
    {
        return $this->mid;
    }
}
