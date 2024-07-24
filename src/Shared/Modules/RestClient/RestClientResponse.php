<?php
namespace App\Shared\RestClient;

namespace App\Shared\Modules\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RestClientResponse
{
    private ResponseInterface $response;
    private SerializerInterface $serializer;

    public function __construct(ResponseInterface $response, SerializerInterface $serializer)
    {
        $this->response = $response;
        $this->serializer = $serializer;
    }

    public function retrieve(): self
    {
        return $this;
    }

    public function toArray(): array
    {
        $this->checkStatusCode();
        return $this->response->toArray();
    }

    public function toEntity(string $class)
    {
        $this->checkStatusCode();
        return $this->serializer->deserialize($this->response->getContent(), $class, 'json');
    }

    public function toEntities(string $class): array
    {
        $this->checkStatusCode();
        return $this->serializer->deserialize($this->response->getContent(), "array<{$class}>", 'json');
    }

    public function onStatus(int $statusCode, callable $callback): void
    {
        if ($this->response->getStatusCode() === $statusCode) {
            $callback($this->response);
        }
    }

    private function checkStatusCode(): void
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode >= 400) {
            throw new \RuntimeException("HTTP request failed with status code {$statusCode}");
        }
    }
}
