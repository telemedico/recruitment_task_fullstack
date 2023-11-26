<?php

namespace Unit\API\NBP;

use App\API\NBP\ApiNBP;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


class ApiNBPTest extends TestCase
{
    /**
     * Test if Api NBP return successfull with valid request. 
     */
    public function testApiNBPIsWorkingWithValidRequest(): void
    {
        $apiNBP = new ApiNBP();
        $response = $apiNBP->getRate('USD','2023-01-02');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test if Api NBP return status code HTTP_BAD_REQUEST with invalid request. 
     */
    public function testApiNBPWithInvalidRequest(): void
    {
        $apiNBP = new ApiNBP();
        $response = $apiNBP->getRate('USD','2023');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    /**
     * Test if Api NBP return correct response data with array key 'code'.
     */
    public function testApiNBPReturnCorrectDataWithValidRequest(): void
    {
        $apiNBP = new ApiNBP();
        $response = $apiNBP->getRate('USD','2023-01-02');
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertTrue(true);
    }
}
