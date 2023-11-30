<?php

declare(strict_types=1);

namespace Currencies\Service\Client\Response;

interface CurrencyInterface
{
    public function getName(): string;
    public function getCode(): string;
    public function getValue(): float;
}