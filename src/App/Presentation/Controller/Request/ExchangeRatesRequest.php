<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;

final class ExchangeRatesRequest extends ValidatedRequest
{
    private const USER_DATE_PARAM = 'userDate';
    private const LATEST_DATE_PARAM = 'latestDate';

    private const DATE_FORMAT = 'Y-m-d';

    protected function assertRequestIsValid(): void
    {
        if (!\DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $this->getRawDate(self::USER_DATE_PARAM))) {
            throw RequestValidationException::withErrors([self::USER_DATE_PARAM => 'Invalid format - `'.self::DATE_FORMAT.'` expected.']);
        }

        if (!\DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $this->getRawDate(self::LATEST_DATE_PARAM))) {
            throw RequestValidationException::withErrors([self::LATEST_DATE_PARAM => 'Invalid format - `'.self::DATE_FORMAT.'` expected.']);
        }
    }

    private function getRawDate(string $paramName): string
    {
        return (string) $this->request->query->get($paramName, '');
    }

    public function getUserDate(): \DateTimeImmutable
    {
        /** @var \DateTimeImmutable $userDate */
        $userDate = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $this->getRawDate(self::USER_DATE_PARAM));

        return $userDate;
    }

    public function getLatestDate(): \DateTimeImmutable
    {
        /** @var \DateTimeImmutable $latestDate */
        $latestDate = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $this->getRawDate(self::LATEST_DATE_PARAM));

        return $latestDate;
    }
}
