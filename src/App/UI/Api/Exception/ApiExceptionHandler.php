<?php

declare(strict_types=1);

namespace App\UI\Api\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
         return [
             KernelEvents::EXCEPTION => 'onKernelException',
         ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof ValidateRequestException) {
            $event->setResponse(
                new JsonResponse(
                    [
                        'error' => $event->getThrowable()->getMessage(),
                    ],
                    $event->getThrowable()->getCode()
                )
            );
        }
    }
}