<?php
declare(strict_types=1);

namespace App\APIClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;;
use Psr\Log\LoggerInterface;

class Delegate
{
    private $httpClient;
    private $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
