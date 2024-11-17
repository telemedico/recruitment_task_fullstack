<?php

declare(strict_types=1);

namespace App\Domain\Query\View;

final class ExchangeRatesView implements \JsonSerializable
{
    private const DATES_FORMAT = 'Y-m-d';

    /**
     * @var \DateTimeImmutable
     */
    private $latestDate;

    /**
     * @var \DateTimeImmutable
     */
    private $userDate;

    /**
     * @var array<ExchangeRateView>
     */
    private $rates;

    public function __construct(
        \DateTimeImmutable $latestDate,
        \DateTimeImmutable $userDate,
        ExchangeRateView ...$rates
    ) {
        $this->latestDate = $latestDate;
        $this->userDate = $userDate;
        $this->rates = $rates;
    }

    public function jsonSerialize(): array
    {
        return [
            'rates' => $this->rates,
            'userDate' => $this->userDate->format(self::DATES_FORMAT),
            'latestDate' => $this->latestDate->format(self::DATES_FORMAT),
        ];
    }
}
