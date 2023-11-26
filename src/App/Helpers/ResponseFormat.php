<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFormat
{
    /**
     * Formats an incorrect response from the server.
     *
     * @param int $status
     * @param string $message
     * @param $details
     * @return JsonResponse
     */
    public static function responseError(int $status, string $message, $details = null): JsonResponse
    {
        return new JsonResponse(
            [
                'error' => [
                    'code' => $status,
                    'message' => $message,
                    'details' => $details
                ]
            ],$status
        );
    }
}