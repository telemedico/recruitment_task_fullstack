<?php

declare(strict_types = 1);

namespace App\ExchangeRate;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, string $message = '', int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($message, $data), $status, $headers, $json);
    }

    private function format(string $message, $data = []): array
    {
        return [
            'message' => $message,
            'data' => $data
        ];
    }
}