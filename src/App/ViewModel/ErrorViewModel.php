<?php

namespace App\ViewModel;

use JsonSerializable;

class ErrorViewModel implements JsonSerializable
{
    /** @var string $message */
    private $message;
    /** @var bool $error */
    private $error;

    public function __construct(string $message, bool $error)
    {
        $this->message = $message;
        $this->error = $error;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isError(): bool
    {
        return $this->error;
    }

    public function jsonSerialize(): array
    {
        return [
            'error' => true,
            'message' => $this->message
        ];
    }
}