<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class ValidatedRequest
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \LogicException('Request must exist!');
        }

        $this->request = $request;

        $this->assertRequestIsValid();
    }

    /**
     * @throws RequestValidationException
     */
    abstract protected function assertRequestIsValid(): void;
}
