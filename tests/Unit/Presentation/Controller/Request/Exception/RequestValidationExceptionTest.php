<?php

declare(strict_types=1);

namespace App\Tests\Unit\Presentation\Controller\Request\Exception;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use PHPUnit\Framework\TestCase;

final class RequestValidationExceptionTest extends TestCase
{
    public function testWithErrorsCreatesException(): void
    {
        $errors = [
            'userDate' => 'Invalid format - `Y-m-d` expected.',
            'latestDate' => 'This field is required.',
        ];

        $exception = RequestValidationException::withErrors($errors);

        self::assertInstanceOf(RequestValidationException::class, $exception);

        self::assertSame('Request validation failed.', $exception->getMessage());

        $headers = $exception->getHeaders();

        self::assertArrayHasKey('X-Validation-Errors', $headers);

        $validationErrors = \json_decode($headers['X-Validation-Errors'], true);
        self::assertSame($errors, $validationErrors);
    }
}
