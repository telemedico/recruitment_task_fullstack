<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class RequestValidationException extends BadRequestHttpException
{
    /**
     * @param array<string, string> $errors
     */
    public static function withErrors(array $errors): self
    {
        return new self('Request validation failed.', null, 0, ['X-Validation-Errors' => \json_encode($errors)]);
    }
}
