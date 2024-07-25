<?php

namespace Integration\RestClient;

use App\Shared\Modules\RestClient\Exceptions\RestClientRequestException;
use App\Shared\Modules\RestClient\RestClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RestClientTest extends KernelTestCase
{
    private $restClient;

    private $httpClientMock;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->restClient = new RestClient($this->httpClientMock, $container->get('serializer'));
    }

    public function testGetRequestSuccess(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getContent')->willReturn(json_encode(['key' => 'value']));

        $this->httpClientMock->method('request')->willReturn($responseMock);

        $response = $this->restClient->get('https://example.com');
        $data = $response->toArray();

        $this->assertEquals(['key' => 'value'], $data);
    }

    public function testGetRequestFailure(): void
    {
        $this->expectException(RestClientRequestException::class);

        $transportException = new TransportException('Network error');
        $this->httpClientMock->method('request')->willThrowException($transportException);

        $this->restClient->get('https://example.com');
    }
}
