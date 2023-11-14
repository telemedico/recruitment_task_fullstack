<?php

namespace App\Http\Nbp;

use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpHttpClient implements NbpClient
{
    public const ENDPOINT_TABLES = 'tables';

    /**
     * @var HttpClientInterface
     */
    private $nbpClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(HttpClientInterface $nbpClient, LoggerInterface $logger)
    {
        $this->nbpClient = $nbpClient;
        $this->logger = $logger;
    }

    public function getTablesForDate(DateTime $date, string $tableName = 'A'): array
    {
        try {
            $response = $this->nbpClient->request(
                'GET',
                sprintf('%s/%s/%s', self::ENDPOINT_TABLES, $tableName ,$date->format('Y-m-d'))
            );
            $this->logger->info($response->getInfo('debug'));

            return json_decode($response->getContent(), true);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Failed to getTablesForDate: %s', $e->getMessage()));
            return [];
        }
    }
}