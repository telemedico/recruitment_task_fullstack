<?php

declare(strict_types=1);

namespace Currencies\Service\Client\NBP\Response;

use Currencies\Service\Client\Response\CurrencyInterface;

class Currency implements CurrencyInterface
{
    private $name;
    private $code;
    private $value;

    public function __construct(string $name, string $code, float $value) {
        $this->name = $name;
        $this->code = $code;
        $this->value = $value;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCode(): string {
        return $this->code;
    }
    public function getValue(): float {
        return $this->value;
    }
}