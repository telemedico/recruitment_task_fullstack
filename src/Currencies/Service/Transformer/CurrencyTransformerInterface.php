<?php

namespace Currencies\Service\Transformer;

interface CurrencyTransformerInterface
{
    public function transform(array $currencyResponse): array;
}