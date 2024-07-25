<?php
namespace App\Shared\Modules\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Shared\Modules\RestClient\Exceptions\RestClientResponseException;

class RestClientResponse
{
    private ResponseInterface $response;
    private SerializerInterface $serializer;

    public function __construct(ResponseInterface $response, SerializerInterface $serializer)
    {
        $this->response = $response;
        $this->serializer = $serializer;
        $this->checkForErrors();
    }

    private function checkForErrors(): void
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode >= 400) {
            throw new RestClientResponseException("HTTP request failed with status code {$statusCode}", $statusCode);
        }
    }

    public function toArray(): array
    {
        return json_decode($this->response->getContent(), true);
    }

    public function toEntity(string $className)
    {
        return $this->serializer->deserialize($this->response->getContent(), $className, 'json');
    }

    public function toEntities(string $className): array
    {
        return $this->serializer->deserialize($this->response->getContent(), $className . '[]', 'json');
    }
}