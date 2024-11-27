<?php

namespace App\Controller;

use App\Exception\CurrencyValueNotFoundException;
use App\Exception\DateNotValidException;
use App\ViewModel\ErrorViewModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $model = new ErrorViewModel($exception->getMessage(), true);
        if ($exception instanceof DateNotValidException) {
            $this->setResponse($event, $model, 400);
        }
        if ($exception instanceof CurrencyValueNotFoundException) {
            $this->setResponse($event, $model, 404);
        }
    }

    private function setResponse(ExceptionEvent $event, ErrorViewModel $model, int $statusCode): void
    {
        $event->setResponse(new JsonResponse(
            $model,
            $statusCode
        ));
    }
}