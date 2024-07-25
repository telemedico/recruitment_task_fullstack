<?php
namespace App\Shared\Modules\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Shared\Modules\RestClient\Exceptions\RestClientRequestException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RestClient
{
    private HttpClientInterface $client;
    private SerializerInterface $serializer;

    public function __construct(HttpClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function get(string $url, array $headers = []): RestClientResponse
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => $headers,
            ]);
            return new RestClientResponse($response, $this->serializer);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new RestClientRequestException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function post(string $url, array $data, array $headers = []): RestClientResponse
    {
        try {
            $response = $this->client->request('POST', $url, [
                'headers' => $headers,
                'json' => $data,
            ]);
            return new RestClientResponse($response, $this->serializer);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new RestClientRequestException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
