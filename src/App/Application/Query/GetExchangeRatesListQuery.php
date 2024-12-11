<?php

declare(strict_types=1);

namespace App\Application\Query;

use DateTimeImmutable;

final class GetExchangeRatesListQuery
{
    /**
     * @var DateTimeImmutable
     */
    private $date;

    public function __construct(
        DateTimeImmutable $date
    ) {
        $this->date = $date;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
