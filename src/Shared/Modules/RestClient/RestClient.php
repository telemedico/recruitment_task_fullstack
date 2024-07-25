<?php
namespace App\Shared\Modules\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Shared\Modules\RestClient\Exceptions\RestClientRequestException;
use App\Shared\Modules\RestClient\Exceptions\RestClientResponseException;

class RestClient
{
    private HttpClientInterface $httpClient;
    private SerializerInterface $serializer;

    public function __construct(HttpClientInterface $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function get(string $url): RestClientResponse
    {
        try {
            $response = $this->httpClient->request('GET', $url);
            return new RestClientResponse($response, $this->serializer);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new RestClientRequestException('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function post(string $url, array $data): RestClientResponse
    {
        try {
            $response = $this->httpClient->request('POST', $url, [
                'json' => $data,
            ]);
            return new RestClientResponse($response, $this->serializer);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new RestClientRequestException('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
