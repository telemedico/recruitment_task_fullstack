<?php

declare(strict_types=1);

namespace App\UI\Api\Validator;

use App\UI\Api\Exception\ValidateRequestException;
use DateTimeImmutable;

final class GetExchangeRatesListValidator
{
    /**
     * @throws ValidateRequestException
     */
    public static function validate(DateTimeImmutable $date): void
    {
        $today = new DateTimeImmutable();
        $minDate = new DateTimeImmutable('2023-01-01');

        if ($date > $today || $date < $minDate) {
            throw ValidateRequestException::create('date');
        }
    }
}
