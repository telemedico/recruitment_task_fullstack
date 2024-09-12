<?php

namespace App\Factory;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class HttpClientFactory
{
    private $baseUri;

    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function createHttpClient(): HttpClientInterface
    {
        return HttpClient::createForBaseUri($this->baseUri);
    }
}
