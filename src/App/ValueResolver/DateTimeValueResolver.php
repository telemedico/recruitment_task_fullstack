<?php

namespace App\ValueResolver;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DateTimeValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === DateTime::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        try {
            $dateString = $request->get($argument->getName());
            if ($dateString) {
                yield new DateTime($dateString);
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException(
                sprintf("Path argument '%s' has to be date/time string", $argument->getName())
            );
        }
    }
}