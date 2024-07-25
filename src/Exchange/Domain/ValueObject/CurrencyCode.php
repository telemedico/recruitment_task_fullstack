<?php

declare(strict_types=1);

namespace App\Exchange\Domain\ValueObject;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Webmozart\Assert\Assert;

final class CurrencyCode
{
    /**
     * @Groups("write")
     * @SerializedName("code")
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assert::length($value, 3, 'Currency code must be exactly 3 characters.');
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(CurrencyCode $other): bool
    {
        return $this->value === $other->getValue();
    }
}
