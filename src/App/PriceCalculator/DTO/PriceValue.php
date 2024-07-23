<?php
declare(strict_types=1);

namespace App\PriceCalculator\DTO;

class PriceValue
{
    /** @var float  */
    public $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }
}
