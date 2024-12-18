<?php

namespace App\Interface\ExchangeRate;

interface ExchangeRateProviderInterface
{
    /**
     * @param \DateTimeInterface $date
     * @return array
     */
    public function getRatesForDate(\DateTimeInterface $date);
}