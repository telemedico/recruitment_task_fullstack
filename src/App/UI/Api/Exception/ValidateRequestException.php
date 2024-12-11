<?php

declare(strict_types=1);

namespace App\UI\Api\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class ValidateRequestException extends Exception
{
    public static function create(string $fieldName): self
    {
        return new self('Invalid ' . $fieldName, Response::HTTP_BAD_REQUEST);
    }
}
