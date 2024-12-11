<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;
use ReflectionClass;

final class Currency
{
    public const USD = 'USD';
    public const EUR = 'EUR';
    public const CZK = 'CZK';
    public const IDR = 'IDR';
    public const BRL = 'BRL';

    private $code;
    private $name;

    public function __construct(string $code, $name)
    {
        $this->validate($code);

        $this->code = strtoupper($code);
        $this->name = $name;
    }

    public static function getAvailableCurrencies(): array
    {
        $ref = new ReflectionClass(self::class);

        return array_values($ref->getConstants());
    }

    private function validate(string $code): void
    {
        if (!in_array(strtoupper($code), self::getAvailableCurrencies(), true)) {
            throw new InvalidArgumentException('Invalid currency code');
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
