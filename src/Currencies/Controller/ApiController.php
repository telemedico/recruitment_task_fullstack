<?php

declare(strict_types=1);

namespace Currencies\Controller;

use Currencies\Exception\CurrencyNotFound;
use Currencies\Service\Currency\ProviderInterface as CurrencyProviderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController extends AbstractController
{
    private $provider;
    private $logger;

    public function __construct(
        CurrencyProviderInterface $provider,
        LoggerInterface $logger
    ) {
        $this->provider = $provider;
        $this->logger = $logger;
    }

    public function getAll(string $date) {
        $dateTime = new \DateTime($date);

        try {
            $currencies = $this->provider->getCurrencies($dateTime);
        } catch (CurrencyNotFound $e) {
            $this->logger->log(LogLevel::ERROR, $e->getMessage() . ': ' . $e->getTraceAsString());
            throw new NotFoundHttpException($e->getMessage());
        }

        return JsonResponse::create($currencies);
    }
}