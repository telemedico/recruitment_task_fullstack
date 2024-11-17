<?php

declare(strict_types=1);

namespace App\Infrastruture\Api\NBP;

use App\Application\Api\NBP\NbpApiInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NbpApi implements NbpApiInterface
{
    private const DATE_PATAM_FORMAT = 'Y-m-d';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $baseUrl;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->logger = $logger;
    }

    public function fetchExchangeRatesForDate(\DateTimeImmutable $date): array
    {
        if (!$this->requestShouldBeRun($date)) {
            $this->logger->warning('Request to NBP API cannot be done.', ['date' => $date]);

            return [];
        }

        $response = $this->client
            ->request(Request::METHOD_GET,
                sprintf(
                    '%s/api/exchangerates/tables/A/%s/?format=json',
                    $this->baseUrl,
                    $date->format(self::DATE_PATAM_FORMAT)
                )
            );

        if (Response::HTTP_OK === $response->getStatusCode()) {
            return $response->toArray(false);
        }

        $this->logger->warning('Unexpected response from NBP API', ['response' => $response]);

        return [];
    }

    private function requestShouldBeRun(\DateTimeImmutable $date): bool
    {
        return $date->setTime(0, 0) <= (new \DateTimeImmutable())->setTime(0, 0);
    }
}
