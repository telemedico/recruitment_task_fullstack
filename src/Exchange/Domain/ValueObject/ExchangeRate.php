<?php

declare(strict_types=1);

namespace App\Exchange\Domain\ValueObject;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Webmozart\Assert\Assert;

final class ExchangeRate
{
    /**
     * @Groups("write")
     * @SerializedName("rate")
     * @var float
     */
    private $value;

    public function __construct(float $value)
    {
        Assert::greaterThan($value, 0, 'Exchange rate must be greater than 0.');
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(ExchangeRate $other): bool
    {
        return $this->value === $other->getValue();
    }
}
