<?php

namespace Services\NbpApiServiceTest;

use App\Utils\PHPUnitUtils;
use PHPUnit\Framework\TestCase;
use App\Service\NbpApiService;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class NbpApiServiceForTest extends NbpApiService {

    public static function createClient() {
        return new MockHttpClient([
            new MockResponse(json_encode([[]]), ['http_code' => 200]),
        ]);
    }
}

class NbpApiServiceForTest2 extends NbpApiService {

    public static function createClient() {
        return new MockHttpClient([
            new MockResponse(json_encode([[]]), ['http_code' => 200]),
            new MockResponse(json_encode([[]]), ['http_code' => 200]),
            ['http_code' => 200]
        ]);
    }
}

class NbpApiServiceTest extends TestCase
{
    public function testFetchNbpApi(): void {
        $service = new NbpApiServiceForTest();
        $this->assertEquals(
            $service->fetchNbpApi(null, true),
            [
                onlyLatestData => true,
                latest=> []
            ]
        );

        $this->assertEquals(
            $service->fetchNbpApi('2023-01-10', true),
            [
                onlyLatestData => true,
                latest=> []
            ]
        );
    }

    public function testFetchNbpApiHistorical(): void {
        $service = new NbpApiServiceForTest2();
        $this->assertEquals(
            $service->fetchNbpApi('2023-01-10', false),
            [
                onlyLatestData => false,
                latest=> [],
                historical => [],
            ]
        );
    }
}