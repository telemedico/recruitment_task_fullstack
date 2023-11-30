<?php

declare(strict_types=1);

namespace Currencies\Data;

use Currencies\Data\Currency\Rates;

class Currency implements CurrencyInterface, \JsonSerializable
{
    private $code;
    private $name;
    private $icon;
    private $rates;

    public function __construct(
        string $name,
        string $code,
        string $icon,
        Rates $rates
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->icon = $icon;
        $this->rates = $rates;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'icon' => $this->icon,
            'rates' => $this->rates
        ];
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getIcon(): string {
        return $this->icon;
    }

    public function getRates(): Rates {
        return $this->rates;
    }
}