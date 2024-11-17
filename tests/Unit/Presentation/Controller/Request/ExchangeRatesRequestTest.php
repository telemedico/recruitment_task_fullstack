<?php

declare(strict_types=1);

namespace App\Tests\Unit\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use App\Presentation\Controller\Request\ExchangeRatesRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ExchangeRatesRequestTest extends TestCase
{
    private const VALID_USER_DATE = '2024-11-10';
    private const VALID_LATEST_DATE = '2024-11-17';
    private const INVALID_DATE = '17-11-2024';
    private const DATE_FORMAT = 'Y-m-d';

    public function testValidRequest(): void
    {
        $requestStack = $this->createRequestStack([
            'userDate' => self::VALID_USER_DATE,
            'latestDate' => self::VALID_LATEST_DATE,
        ]);

        $validatedRequest = new ExchangeRatesRequest($requestStack);

        self::assertInstanceOf(\DateTimeImmutable::class, $validatedRequest->getUserDate());
        self::assertSame(self::VALID_USER_DATE, $validatedRequest->getUserDate()->format(self::DATE_FORMAT));

        self::assertInstanceOf(\DateTimeImmutable::class, $validatedRequest->getLatestDate());
        self::assertSame(self::VALID_LATEST_DATE, $validatedRequest->getLatestDate()->format(self::DATE_FORMAT));
    }

    public function testInvalidUserDateThrowsException(): void
    {
        $requestStack = $this->createRequestStack([
            'userDate' => self::INVALID_DATE,
            'latestDate' => self::VALID_LATEST_DATE,
        ]);

        $this->expectExceptionObject(
            RequestValidationException::withErrors(['date' => 'Invalid format - `Y-m-d` expected.']));

        new ExchangeRatesRequest($requestStack);
    }

    public function testInvalidLatestDateThrowsException(): void
    {
        $requestStack = $this->createRequestStack([
            'userDate' => self::VALID_USER_DATE,
            'latestDate' => self::INVALID_DATE,
        ]);

        $this->expectExceptionObject(
            RequestValidationException::withErrors(['date' => 'Invalid format - `Y-m-d` expected.']));

        new ExchangeRatesRequest($requestStack);
    }

    public function testMissingUserDateThrowsException(): void
    {
        $requestStack = $this->createRequestStack([
            'latestDate' => self::VALID_LATEST_DATE,
        ]);

        $this->expectExceptionObject(
            RequestValidationException::withErrors(['date' => 'Invalid format - `Y-m-d` expected.']));

        new ExchangeRatesRequest($requestStack);
    }

    public function testMissingLatestDateThrowsException(): void
    {
        $requestStack = $this->createRequestStack([
            'userDate' => self::VALID_USER_DATE,
        ]);

        $this->expectExceptionObject(
            RequestValidationException::withErrors(['date' => 'Invalid format - `Y-m-d` expected.']));

        new ExchangeRatesRequest($requestStack);
    }

    private function createRequestStack(array $queryParameters): RequestStack
    {
        $request = new Request($queryParameters);
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
