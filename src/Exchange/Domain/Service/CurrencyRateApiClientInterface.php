<?php
namespace App\Exchange\Domain\Service;

interface CurrencyRateApiClientInterface
{
    public function getExchangeRate(string $currency, string $date): array;
}
