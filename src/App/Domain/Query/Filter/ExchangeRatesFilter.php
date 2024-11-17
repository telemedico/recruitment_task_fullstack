<?php

declare(strict_types=1);

namespace App\Domain\Query\Filter;

final class ExchangeRatesFilter
{
    /**
     * @var \DateTimeImmutable
     */
    private $userDate;

    /**
     * @var \DateTimeImmutable
     */
    private $latestDate;

    public function __construct(
        \DateTimeImmutable $userDate,
        \DateTimeImmutable $latestDate
    ) {
        $this->userDate = $userDate;
        $this->latestDate = $latestDate;
    }

    public function getUserDate(): \DateTimeImmutable
    {
        return $this->userDate;
    }

    public function getLatestDate(): \DateTimeImmutable
    {
        return $this->latestDate;
    }
}
