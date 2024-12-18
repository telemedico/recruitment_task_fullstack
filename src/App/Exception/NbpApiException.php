<?php

declare(strict_types=1);

namespace App\Exception;

class NbpApiException extends \Exception
{
    public static function fromResponse(string $message): self
    {
        return new self(sprintf('NBP API error: %s', $message));
    }
}