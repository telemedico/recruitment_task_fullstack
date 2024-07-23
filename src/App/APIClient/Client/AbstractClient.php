<?php
declare(strict_types=1);

namespace App\NBPApi\Client;

use App\APIClient\Delegate;
use App\APIClient\Exceptions\ResponseBuildException;
use Symfony\Component\HttpClient\Response\CurlResponse;
use App\Utils\Json;

abstract class AbstractClient implements ClientInterface
{
    protected $method;

    /** @var array<string, string[]> */
    protected $headers = [];

    /** @var array<mixed, mixed> */
    protected $request = [];

    protected $endpoint;

    protected $delegate;

    /**
     * AbstractClient constructor
     *
     * @param string   $endpoint Target endpoint.
     * @param Delegate $delegate Delegate instance.
     */
    public function __construct(string $endpoint, Delegate $delegate)
    {
        $this->endpoint = $endpoint;
        $this->delegate = $delegate;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\BaseException
     *
     * @return Response\ResponseInterface
     */
    public function send(): array
    {
        $rawResponse = $this->delegate->getHttpClient()->request(
            $this->method,
            $this->buildUri(),
            $this->buildOptions()
        );
        return $this->deserializeResponse($rawResponse);
    }

    /**
     * Build the URI for the request
     *
     * @return string
     */
    protected function buildUri(): string
    {
        return $this->endpoint;
    }

    /**
     * Build the options for the request
     *
     * @return array<string, array<string, string[]>>
     */
    protected function buildOptions(): array
    {
        return [
            'headers' => $this->headers,
        ];
    }

    /**
     * Deserialize the CurlResponse into a ResponseInterface
     *
     * @param CurlResponse $response
     * @return array
     * @throws ResponseBuildException
     */
    protected function deserializeResponse(CurlResponse $response): array
    {
        try {
            return Json::decode($response->getContent());
        } catch (\Throwable $t) {
            $this->delegate->getLogger()->error("API Client error: {$t->getMessage()}");
            throw new ResponseBuildException('Cannot fetch and deserialize response content');
        }
    }
}
