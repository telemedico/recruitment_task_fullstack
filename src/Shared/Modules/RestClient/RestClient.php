<?php
namespace App\Shared\Modules\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        $response = $this->client->request('GET', $url, [
            'headers' => $headers,
        ]);

        return new RestClientResponse($response, $this->serializer);
    }
}
