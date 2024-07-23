<?php
declare(strict_types=1);

namespace App\NBPApi;

use App\APIClient\Delegate;
use App\NBPApi\Client\GetClient;
use App\NBPApi\Exceptions\InvalidDateParameter;
use App\NBPApi\Validator\Validator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;;
use Psr\Log\LoggerInterface;
use App\NBPApi\DTO\ExchangesRatesDTO;

class Client
{
    /** @var Delegate  */
    private $delegate;

    /** @var string  */
    private $baseUrl;

    private $requestStack;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $baseUrl,
        RequestStack $requestStack)
    {
        $this->delegate = new Delegate($httpClient, $logger);
        $this->baseUrl = $baseUrl;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $selectedRates
     * @param string|null $date
     * @return ExchangesRatesDTO
     * @throws InvalidDateParameter
     */
    public function getExchangeRates(array $selectedRates = [], ?string $date): ExchangesRatesDTO
    {
        if (!Validator::validateDate($date)) {
            throw new InvalidDateParameter('Invalid date parameter');
        }

        return ExchangesRatesDTO::fromResponse(
            (new GetClient("$this->baseUrl/exchangerates/tables/A/$date", $this->delegate))->send(),
            $selectedRates
        );
    }
}
