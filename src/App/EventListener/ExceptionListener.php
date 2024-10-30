<?php

declare(strict_types = 1);

namespace App\EventListener;

use App\ExchangeRate\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $event->setResponse($this->generateApiResponse($event->getThrowable()));
    }

    private function generateApiResponse(Throwable $exception): ApiResponse
    {
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        return new ApiResponse($exception->getMessage(), '', $statusCode);
    }
}