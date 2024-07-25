<?php

declare(strict_types=1);

namespace App\Exchange\Domain\ValueObject;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Webmozart\Assert\Assert;

final class CurrencyName
{
    /**
     * @Groups("write")
     * @SerializedName("name")
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, 1, 255, 'Currency name must be between 1 and 255 characters.');
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

    public function equals(CurrencyName $other): bool
    {
        return $this->value === $other->getValue();
    }
}
